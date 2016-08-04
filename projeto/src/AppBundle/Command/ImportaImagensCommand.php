<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportaImagensCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('importa_imagens')->setDescription('Importação de imagens dos produtos');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {	
    	$dir = new \RecursiveDirectoryIterator("C:\\dev\\projetos\\fotos_app-2016-01-26\\fotos app\\");
    	
    	$verifica_id_produto = array();
    	
    	foreach (new \RecursiveIteratorIterator($dir) as $filename=>$cur) {
    		$sku_produto = substr(basename($filename), 0,4);
    		$extensao_imagem = substr(basename($filename), -3);
    		
    		if(is_numeric($sku_produto) && strtolower($extensao_imagem) == 'png'){
    			$em = $this->getDoctrine()->getManager();
    	
				$query = $em->getConnection()->createQueryBuilder()
    				->select('p.id_produto as id_produto')
    				->from('produto', 'p')
    				->where('p.sku = :sku')
    				->setParameter('sku',$sku_produto)
    				->execute();
    		 
    			$id_produto = $query->fetch();
    			
    			if($id_produto['id_produto'] != ''){
    				if(!in_array($id_produto['id_produto'], $verifica_id_produto)){
    				
    				$verifica_id_produto[] = $id_produto['id_produto'];
    					
    				echo 'inserindo id_produto '.$id_produto['id_produto'];
    				$input_image = $filename;
    				
    				$size = getimagesize( $input_image );
    				$thumb_width = "200";
    				$thumb_height = ( int )(( $thumb_width/$size[0] )*$size[1] );
    				$thumbnail = ImageCreateTrueColor( $thumb_width, $thumb_height );
    				$background = imagecolorallocate($thumbnail, 0, 0, 0);
    				imagecolortransparent($thumbnail, $background);
    				imagealphablending($thumbnail, false);
    				imagesavealpha($thumbnail, true);
    				$src_img = ImageCreateFromPNG( $input_image );
    				ImageCopyResampled( $thumbnail, $src_img, 0, 0, 0, 0, $thumb_width, $thumb_height, $size[0], $size[1] );
    				 
    				ob_start();
    				ImagePNG( $thumbnail);
    				$contents = ob_get_contents();
    				ob_end_clean();
    				 
    				$imagem = "data:image/png;base64," . base64_encode($contents);
    				
    				$query = $em->getConnection()->createQueryBuilder()
    				->insert('produto_imagem')
    				->values(
        				array(
            				'id_produto' => '?',
            				'imagem' => '?'
        				)
    				)
    				->setParameter(0, $id_produto['id_produto'])
    				->setParameter(1, $imagem)
    				->execute();
    				//echo 'sku = '.$sku_produto.' imagem = '.$imagem.' id produto = '.$id_produto['id_produto']."\n";
    			}
    			}
    		}
    	}
    }
    
    public function getDoctrine(){
    	$em = $this->getContainer()->get('doctrine');
    	return $em;
    }
}