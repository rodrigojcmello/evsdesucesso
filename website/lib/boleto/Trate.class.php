<?php

class Treat{
	public function cpf($cpf){
		$cpf = str_split($cpf);
		$newCpf = '';

		for ($i=0; $i < count($cpf); $i++) {
			$newCpf .= $cpf[$i];
			if( $i == 2 ||  $i == 5 ){
				$newCpf .= '.';
			}
			if( $i == 8 ){
				$newCpf .= '-';
			}
		}

		return $newCpf;
	}
}

?>