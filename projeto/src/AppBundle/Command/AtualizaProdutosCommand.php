<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AtualizaProdutosCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('atualiza_produtos')->setDescription('Atualização de produtos');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {	
    	$arq = file('C:\\tmp\\planilha.csv');
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	foreach ($arq as $key => $linhas) {
    		
    		if($key > 0){
    		
    		$quebra = explode(';', $linhas);
    		
    		$id_produto = $quebra[0];
    		$apelido = utf8_encode($quebra[4]);
    		$tag = $quebra[5];
    		$tag_parent = $quebra[6];
    		$visivel = $quebra[7];
    		$exibir_produtos = utf8_encode($quebra[8]);
    		
    		if($exibir_produtos == 'sim'){
    			$exibir_produtos = 'true';
    		}else $exibir_produtos = 'false';
    		
    		if($tag != ''){
    			$query = $em->getConnection()->createQueryBuilder()
    			->select('t.id_tag as id_tag')
    			->from('tag', 't')
    			->where('t.nome = :nome')
    			->setParameter('nome',$tag)
    			->execute();
    			 
    			$id_tag = $query->fetch();
    			
    			if($tag_parent != ''){
    				$query = $em->getConnection()->createQueryBuilder()
    				->select('t.id_tag as id_tag')
    				->from('tag', 't')
    				->where('t.nome = :nome')
    				->setParameter('nome',$tag_parent)
    				->execute();
    				
    				$id_tag_parent = $query->fetch();
    				
    				$ids_tag_parent = $id_tag_parent['id_tag'];
    			}else{
    				$ids_tag_parent = 'NULL';
    			}
    			
    			if(empty($id_tag['id_tag'])){
    				$query = $em->getConnection()->createQueryBuilder()
    				->insert('tag')
    				->values(
    						array(
    								'nome' => '?',
    								'visivel' => '?',
    								'exibir_auto_produtos' => '?',
    								'exibir_categoria' => '?'
    						)
    						)
    						->setParameter(0, $tag)
    						->setParameter(1, $visivel)
    						->setParameter(2, $exibir_produtos)
    						->setParameter(3, 'false')
    						->execute();
    				
    				$query = $em->getConnection()->createQueryBuilder()
    					->select('t.id_tag as id_tag')
    					->from('tag', 't')
		    			->where('t.nome = :nome')
		    			->setParameter('nome',$tag)
		    			->execute();
		    			 
		    			$id_tag = $query->fetch();
    			}
    			
    			$atualiza_id_parent = $em->getConnection()->createQueryBuilder()
    			->update('tag')
    			->set('id_parent', $ids_tag_parent)
    			->where('id_tag = '.$id_tag['id_tag'])
    			->execute();
    		}
    		
    		$query = $em->getConnection()->createQueryBuilder()
    		->insert('tag_produto')
    		->values(
    				array(
    						'id_produto' => '?',
    						'id_tag' => '?'
    				)
    				)
    				->setParameter(0, $id_produto)
    				->setParameter(1, $id_tag['id_tag'])
    				->execute();
    		
    		if($id_produto != ''){
    				
    				echo 'atualizando apelido id_produto '.$id_produto;
    				
    				$atualiza_apelido = $em->getConnection()->createQueryBuilder()
    					->update('produto')
    					->set('apelido', "'$apelido'")
    					->where('id_produto = '.$id_produto)
    					->execute();
    				
    				//echo 'sku = '.$sku_produto.' imagem = '.$imagem.' id produto = '.$id_produto['id_produto']."\n";
    		}
    	}
    	}
    }
    
    public function getDoctrine(){
    	$em = $this->getContainer()->get('doctrine');
    	return $em;
    }
}