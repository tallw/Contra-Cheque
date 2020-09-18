<?php 
session_start();
include('login/config.php');

//if ($logado != "SIM") {		
//	header("Location: nao_logado.php");	
//}
include("mpdf60/mpdf.php");

$ano = (isset($_GET['ano'])) ? $_GET['ano'] : '';
$mes = (isset($_GET['mes'])) ? $_GET['mes'] : '';
$cpf_user = (isset($_GET['cpf'])) ? $_GET['cpf'] : '';
$pegacpf = $_SESSION['usuario'];
 
if($cpf_user === $pegacpf ){ //$pegacpf "nadadisso"
	$con_string = "host=ecospb.ddns.net port=5432 dbname=persona21062018 user=postgres password=postgres";
    if(!$dbcon = pg_connect($con_string)) die ("Erro ao conectar ao banco<br>".pg_last_error($dbcon));


    $sql = "SELECT tr.datarescisao, tr.motivorescisao, tr.codigo matricula, tr.cpf cpf, tr.nome nome_pessoa, cal.ano ano, cal.mes mes, dp.nome departamento, tr.cbo cbo, cal.valor valor, ev.codigo cod_evento, cal.referencia referencia, ev.nome nome_evento, ev.tipovalor tipo_evento
        FROM persona.calculostrabalhadores cal, persona.departamentos dp, persona.trabalhadores tr, persona.eventos ev
        where tr.trabalhador = cal.trabalhador and ev.evento = cal.evento and dp.departamento = tr.departamento and tr.cpf = '{$cpf_user}' and mes = '{$mes}' and ano = '{$ano}'
        order by ano, mes, cal.trabalhador
        limit 100";

            //'{$cpf_user}'select tr.nome 

    $query = pg_query($dbcon, $sql);

    pg_close($dbcon);



    $matricula = null;
    $nome_pessoa = null;
    $cbo = null;
    $departamento = null;

    // periodo

    $meses = array('Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

    $periodo = "".$meses[$mes-1]."/".$ano;

    $eventos = array();

    // dados de baixo:
    $salario_base = null;
    $base_inss = null;
    $base_calc_fgts = null;
    $fgts_mes = null;
    $base_calc_irrf = null;
    $faixa_irrf = null;

    $total_vencimentos = 0;
    $total_descontos = 0;

    $situacao = '';
    $data_rescisao = '';

    $cont = 0;
    $teto = 621.03;
    $ref_inss = 0;
    $retirar = array('0005', 'RJ0005', '0076', '0039', '0031', '0037', '0036', '0024', '0077', '0064', '0126', '0127', '0033', '0096', '0090', '0065', '0076RJ', '0033RJ', '0039RJ', '0031RJ', '0037RJ', '0036RJ', '0024RJ', '0077RJ', '0127RJ', '0096RJ', '0090RJ', '0065RJ'); 

    while ($linha = pg_fetch_array($query) ) {

        $situacao = $linha['motivorescisao'];
        $data_rescisao = $linha['datarescisao'];

        if($cont == 0){
            $matricula = $linha['matricula'];
        
            $nome_pessoa = $linha['nome_pessoa'];
            $cbo = $linha['cbo'];
            $departamento = $linha['departamento'];
        
        }


        

        //######################################################################################################## Futuramente gerar um contracheque só para os eventos com cod 0039, 0031, 0037, 0036 e 0135 no Mês de Dezembro

        if(($linha['tipo_evento'] == 0 || $linha['tipo_evento'] == 1) && (!in_array($linha['cod_evento'], $retirar))){
            // (cod, descrição, ref, valor(vencimento ou desconto) depende do, tipoValor)

            array_push($eventos, array($linha['cod_evento'], $linha['nome_evento'], $linha['referencia'], $linha['valor'], $linha['tipo_evento']));
            //$temp = $linha['cod_evento'];
            //echo "<script>alert('entrou');</script>";
        
        
        }

        //echo nl2br(($usuario["nome_pessoa"]."-----".$usuario["valor"]."\n"));
        if ($linha['cod_evento'] == '0115') {
            $salario_base = $linha['valor'];
        }elseif ($linha['cod_evento'] == '0088') {
            $ref_inss = $linha['referencia'];   
        }elseif ($linha['cod_evento'] == '0072') {
            $base_calc_fgts = $linha['valor'];
        }elseif ($linha['cod_evento'] == '0076') {
            $fgts_mes += $linha['valor'];
        }else if($linha['cod_evento'] == '0064'){
            $fgts_mes += $linha['valor'];
        }elseif ($linha['cod_evento'] == '0073') {
            $base_calc_irrf = $linha['valor'];
        }

        $cont+=1;
    }

    for ($i=0; $i < count($eventos); $i++) { 
        if($eventos[$i][4] == 0){
            $total_vencimentos += $eventos[$i][3];
        }else{
            $total_descontos += $eventos[$i][3];
        }
    }

    // calculando o teto

    if (strlen($ref_inss) > 1) {
        $ref_inss = '0.'.$ref_inss;
    }else{
        $ref_inss = '0.0'.$ref_inss;
    }

    //$ref_inss

    if($salario_base * $ref_inss > $teto){
        $base_inss = 5645.80;
    }else{
        $base_inss = $salario_base;
    }

    $valor_liquido = $total_vencimentos - $total_descontos;

    $cod = "";
    $descricao = "";
    $ref = "";
    $vencimentos = "";
    $descontos = "";
    $temp = null;

    $qtde = 0;
    for ($i=0; $i < count($eventos); $i++) {
        if($eventos[$i][4] == 0){
            $cod .= nl2br($eventos[$i][0]."\n");
            $descricao .= nl2br($eventos[$i][1]."\n");

            if(strlen($eventos[$i][2]) > 2){
                $temp = substr($eventos[$i][2], 0, 2).'.'.substr($eventos[$i][2], -1);
            }else{
                $temp = $eventos[$i][2];
            }
            if($eventos[$i][2] != null){
                if ($salario_base > 1999.18) {
                    $faixa_irrf = number_format($temp, 1, ',', '.');
                }
                $ref .= nl2br(number_format($temp, 2, ',', '.')."\n");
            }else{
                $ref .= nl2br(" \n");
            }  

            $vencimentos .= nl2br(number_format($eventos[$i][3], 2, ',', '.')."\n");
            $descontos .= nl2br(" \n");
            $qtde +=1;
        }
                
    }


    for ($i=0; $i < count($eventos); $i++) {
        if($eventos[$i][4] == 1){
            $cod .= nl2br($eventos[$i][0]."\n");
            $descricao .= nl2br($eventos[$i][1]."\n");

            if(strlen($eventos[$i][2]) > 2){
                $temp = substr($eventos[$i][2], 0, 2).'.'.substr($eventos[$i][2], -1);
            }else{
                $temp = $eventos[$i][2];
            }
            if($eventos[$i][2] != null){
                if ($salario_base > 1999.18) {
                    $faixa_irrf = number_format($temp, 1, ',', '.');
                }
            
                $ref .= nl2br(number_format($temp, 2, ',', '.')."\n");
            }else{
                $ref .= nl2br(" \n");
            }  

            $vencimentos .= nl2br(" \n");
            $descontos .= nl2br(number_format($eventos[$i][3], 2, ',', '.')."\n");
            $qtde +=1;
        }
                
    }

    if($qtde < 15){
        for ($i=$qtde; $i < 16; $i++) { 
            $cod .= nl2br(" \n");
            $descricao .= nl2br(" \n");
            $ref .= nl2br(" \n");
            $vencimentos .= nl2br(" \n");
            $descontos .= nl2br(" \n");
        }
    }  

 	$html = "<!DOCTYPE html>

	<html>

    <style>
        .table{
            vertical-align: top;
        }

    </style>

	

	<fieldset>

	<!-- ______________________________________________________________________________________________________________________________ -->

	<table class='table' cellspacing=0 cellpadding=2 bordercolor='666633'>
    	<thead>
    	    <tr>
            	<td class='esquerda' width='70%' height='20%'>ESPA&Ccedil;O CIDADANIA E OPORTUNIDADES SOCIAIS</td>
            	<td width='10%' height='20%'></td>
            	<th class='esquerda' width='45%' height='20%'>Recibo de Pagamento de Sal&aacute;rio</th>
            	
        	</tr>
    	</thead>
    	<tbody>
        	<tr>
            	<td class='esquerda' width='70%' height='20%'>Avenida MARECHAL CAMARA, 160 - SALA 821 B</td>
            	<td width='10%' height='20%'></td>
            	<th class='direita' width='45%' height='20%'> </th>
            	
        	</tr>
        	<tr>
            	<td class='esquerda' width='70%' height='20%'>CNPJ: 02.539.959/0001-25</td>
            	<td width='10%' height='20%'></td>
            	<td class='left' width='45%' height='20%'>Per&iacute;odo : ".$periodo."</td>
            	
        	</tr>
    	</tbody>
    	
	</table>

    <!-- ______________________________________________________________________________________________________________________________ -->


	<table class='table' cellspacing=0 cellpadding=1 bordercolor='666633'>
    	<thead>
    	    <tr>
            	<td class='esquerda' width='10%' height='20%'><font size='1'>C&oacute;digo</font></td>
            	<td class='esquerda' width='40%' height='20%'><font size='1'>Nome do Funcion&aacute;rio</font></td>
            	<td class='esquerda' width='10%' height='20%'><font size='1'>CBO</font></td>
                <td class='esquerda' width='60%' height='20%'><font size='1'>Departamento</font></td>
            	
        	</tr>
    	</thead>
    	<tbody>
        	
        	<tr>
            	<td class='esquerda' width='10%' height='20%'>".$matricula."</td>
            	<td name='nome' class='esquerda' width='40%' height='20%'>".$nome_pessoa."</td>
            	<td class='esquerda' width='10%' height='20%'>".$cbo."</td>
                <td class='esquerda' width='30%' height='60%'>".$departamento."</td>
            	
        	</tr>
    	</tbody>
    	
	</table>

    <!-- ______________________________________________________________________________________________________________________________ -->

    <table class='table' border=1 cellspacing=0 cellpadding=2 bordercolor='666633'>
        <thead>
            <tr>
                <td class='center' width='15%' height='20%'>C&oacute;d.</td>
                <td class='center' width='70%' height='20%'>Descri&ccedil;&atilde;o</td>
                <td class='center' width='15%' height='20%'>Ref.</td>
                <td class='center' width='25%' height='20%'>Vencimentos</td>
                <td class='center' width='25%' height='20%'>Descontos</td>
                
            </tr>

        </thead>

        <tbody>

            <tr>
                <td class='left' width='15%' height='60%'><font size='3'>".$cod."</font></td>
                <td class='left' width='70%' height='60%'><font size='3'>".$descricao."</font></td>
                <td class='center' width='15%' height='60%'><font size='3'>".$ref."</font></td>
                <td class='direita' width='25%' height='60%'><font size='3'>".$vencimentos."</font></td>
                <td class='direita' width='25%' height='60%'><font size='3'>".$descontos."</font></td>
            </tr>
                
        </tbody>
        
    </table>

    <!-- ______________________________________________________________________________________________________________________________ -->

	<table class='table' cellspacing=0 cellpadding=2 bordercolor='666633'>
        <thead>
            <tr>
                <td class='esquerda' width='85%' height='50%'></td>
                <td style='border: 1px solid #000;' class='esquerda' width='40%' height='50%'><font size='1'>Total de Vencimentos</font></td>
                <td style='border: 1px solid #000;' class='esquerda' width='30%' height='50%'><font size='1'>Total de descontos</font></td>
            </tr>
        </thead>

        <thead>
            <tr>
                <td class='esquerda' width='85%' height='50%'><br/> <br/></td>
                <td style='border: 1px solid #000;' class='direita' width='40%' height='50%'>".number_format($total_vencimentos, 2, ',', '.')."</td>
                <td style='border: 1px solid #000;' class='direita' width='30%' height='50%'>".number_format($total_descontos, 2, ',', '.')."</td>
            </tr>
        </thead>

        

        <thead>
            <tr>
                <td class='esquerda' width='85%' height='50%'><br/> <br/></td>
                <td style='border: 1px solid #000;' class='esquerda' width='40%' height='50%'><font size='1'>Valor L&iacute;quido </font> &#9648;&#9648;&#9648;&#9648;&#9648;  &#9658;</td>
                <td style='border: 1px solid #000;' class='direita' width='30%' height='50%'>".number_format($valor_liquido, 2, ',', '.')."</td>
            </tr>
        </thead>

        


        
        
        
    </table>

    <!-- ______________________________________________________________________________________________________________________________ -->

    <table class='table' cellspacing=0 cellpadding=2 bordercolor='666633'>
        <thead>
            <tr>
                <td class='center' width='25%' height='20%'><font size='1'>Sal&aacute;rio Base</font></td>
                <td class='center' width='25%' height='20%'><font size='1'>Base INSS</font></td>
                <td class='center' width='25%' height='20%'><font size='1'>Base Calc. FGTS</font></td>
                <td class='center' width='25%' height='20%'><font size='1'>FGTS do M&ecirc;s</font></td>
                <td class='center' width='25%' height='20%'><font size='1'>Base Calc. IRRF</font></td>
                <td class='center' width='25%' height='20%'><font size='1'>Faixa IRRF</font></td>
                
            </tr>

        </thead>

        <thead>
            <tr>
                <td class='center' width='25%' height='20%'>".number_format($salario_base, 2, ',', '.')."</td>
                <td class='center' width='25%' height='20%'>".number_format($base_inss, 2, ',', '.')."</td>
                <td class='center' width='25%' height='20%'>".number_format($base_calc_fgts, 2, ',', '.')."</td>
                <td class='center' width='25%' height='20%'>".number_format($fgts_mes, 2, ',', '.')."</td>
                <td class='center' width='25%' height='20%'>".number_format($base_calc_irrf, 2, ',', '.')."</td>
                <td class='center' width='25%' height='20%'>".$faixa_irrf."</td>
                
            </tr>
        </thead>
        
    </table>

     <!-- ______________________________________________________________________________________________________________________________ -->
		
	</fieldset>


    </html>";


    //file_get_contents("exemplo_html.html");
        $mes_atual = date('m');
        $dia_atual = date('d');
        $ano_atual = date('y');
    
        $mpdf=new mPDF('utf-8', 'A4-L'); 
        $mpdf->charset_in='windows-1252';
        $mpdf->SetDisplayMode('fullpage');                        
        $css = file_get_contents("css/estilo.css");
        $mpdf->WriteHTML($css,1);
        $mpdf->WriteHTML($html);
        //$mpdf->set_paper('utf-8', 'A4-L');

        $arrayData = explode("-",$data_rescisao);

        $ano_r = $arrayData[0];
        $mes_r = $arrayData[1];

        if ($situacao == "02" && $ano == $ano_r && $mes == $mes_r) {
            echo "<script>alert('Nao existe movimentacoes para data informada...');</script>";
            header("Refresh: 2;url=index.php");
        }else if($ano == "20".$ano_atual && $mes_atual == $mes){ ################################################################################## descomentar no fim de janeiro e por 2019 em index.php
            //echo "Nao mes: ".$mes." ano: ".$ano." mes atual: ".$mes_atual." ano atual: ".$ano_atual;
            echo "<script>alert('Nao existe movimentacoes para data informada...');</script>";
            header("Refresh: 2;url=index.php");
        }elseif (intval($mes) == (intval($mes_atual) - 1 ) && $ano == "20".$ano_atual && intval($dia_atual) < 2) {
            //echo "Nao mes: ".$mes." ano: ".$ano." mes atual: ".$mes_atual." ano atual: ".$ano_atual;
            echo "<script>alert('Nao existe movimentacoes para data informada...');</script>";
            header("Refresh: 2;url=index.php");
        }elseif(empty($nome_pessoa)){
        	echo "<script>alert('Nao existe movimentacoes para data informada...');</script>";
            header("Refresh: 2;url=index.php");

        }else{
            //echo "Gerou mes: ".$mes." ano: ".$ano." mes atual: ".$mes_atual." ano atual: ".$ano_atual;
            $mpdf->Output('meucontra-cheque.pdf',D);
        }

        //$mpdf->charset_in='windows-1252';
  

}else{
    //Desculpe,infelizmente noo pode acessar essa opcao!
        echo "<script>alert('Desculpe,infelizmente voce nao pode acessar essa opcao!');</script>";
        header("Refresh: 3;url=login/sair.php");
}

exit;
