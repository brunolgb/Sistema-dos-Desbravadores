<?php
session_start();

// buscando sessão
$id = $_SESSION["id"];
$conselheiro = $_SESSION["conselheiro"];
$classe = $_SESSION["classe"];
$sexo = $_SESSION["sexo"];

include_once("conexao.php"); //conexão


$idReq = $conexao->prepare("SELECT * FROM planejamento where sexo='".$sexo."'");
$idReq->execute();
$idRequisitos = $idReq->fetchAll(PDO::FETCH_ASSOC);

foreach ($idRequisitos as $buscaReq)
{
	foreach ($buscaReq as $idRequi => $valorRequi)
	{
		if ($idRequi === "requisito")
		{
			$guardaSQL .= " AND idCartao <> '".$valorRequi."'";
		}
		
	}
}
// buscando requisito
$br = $conexao->prepare("SELECT idCartao,area,nrequisito,requisito FROM cartao WHERE cartao='".$classe."' $guardaSQL ORDER BY idCartao");
$br->execute();
$requisitos = $br->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="arquivos/icone.png">
	<link rel="stylesheet" type="text/css" href="arquivos/estilo.css" media="all">
	<link rel="stylesheet" type="text/css" href="arquivos/estiloPc.css" media="all">
	<link rel="stylesheet" type="text/css" href="arquivos/estiloCel.css" media="screen">
	<script src="arquivos/estilo.js"></script>
	<title>Planejamento</title>
</head>
<body>
<?php
	include_once("nav.php");
	echo "<div id='escondido'>".$dataJson."</div>";

?>
<div class="corpo">
	<div class="planejamento">
		<h1 class="titulo">Prenchendo Requisitos da classe de <?php echo $classe; ?></h1>
		<form method="post">
			<div>
				<label for="requisito">Requisitos</label>
				<input type="text" name="requisito" id="requisito" required="" onclick="aba()">
				<input type="hidden" name="nrequisito" id="nrequisito">
			</div>
			<section  class="template-requisitos">
				<aside id="fechar" onclick="fechar()">X</aside>
				<h3>Selecione o requisito do cartão de <?php echo $classe; ?></h3>
				<?php
					$cont = 0;
					foreach ($requisitos as $linhas)
					{
						if ($linhas['area'] != "CLASSES AVANÇADAS")
						{
							$area_linha = strstr($linhas['area']," ",true);
						}
						else
						{
							$area_linha = "CA";
						}
						echo "<div class='maeRequisito' id='".$linhas['idCartao']."' onclick='selecionar($cont)'>";
						echo "<div id='reqArea'>".$area_linha."</div>";
						echo "<div id='reqNrequisito'>".$linhas['nrequisito']."</div>";
						echo "<div id='reqRequisito'>".$linhas['requisito']."</div>";
						echo "</div>";
						$cont++;
					}
				?>
				
			</section>
			<div>
				<label for="quando">Quando</label>
				<input type="date" name="quando" id="quando" required="" onchange="verificarData('#quando')">
				<span id="erro"></span>
			</div>
			<div>
				<label for="termino">Termino - <strong>Preencha esse requisito caso vc for utilizar mais de 1 dia</strong></label>
				<input type="date" name="termino" id="termino" onchange="verificarData('#termino')">
				<span id="erro"></span>
			</div>
			<div>
				<label for="como">Como</label>
				<textarea name="como" id="como" required=""></textarea>
			</div>
			<div>
				<label for="material">Material que será usado</label>
				<input type='text' name="material" id="material">
			</div>
			<div>
				<label for="valor">Valor A gastar <span id="erro"></span></label>
				<input type="text" name="valor" id="valor" required="" onchange="monetario()">
			</div>
			<div>
				<label for="onde">Onde</label>
				<input type="text" name="onde" id="onde" required="">
			</div>
			<div>
				<label for="feito">Feito</label>
				<select name="feito" id="feito" required="">
					<option value="s">sim</option>
					<option value="n" selected="">nao</option>
				</select>
			</div>
			<input type="submit" name="enviar" value="Salvar" class="submit">
		</form>
		<?php
		if (isset($_POST['enviar']))
		{
			$cadastro = date("Y-m-d h:m:s");

			//valores para o cadastramento
			$gravarCol = "classe, sexo, ";
			$gravarVal = "'".$classe."', '".$sexo."', ";
			foreach ($_POST as $c => $v)
			{
				if ($c != "requisito" and $c != "enviar")
				{
					if ($c === "nrequisito")
					{
						$c = "requisito";
					}
					else if($c === 'termino')
					{
						if ($v === null or $v === "")
						{
							$v = $_POST["quando"];
						}
					}
					$v = $c === "valor" ? str_replace(",", ".",$v) : $v;
					$gravarCol .= $c.", ";
					$gravarVal .= "'".$v."', ";
				}
			}
			$gravarCol .= "cadastro, conselheiro";
			$gravarVal .= "'".$cadastro."','".$id."'";

			// echo $gravarCol."<br>".$gravarVal;

			$salvarPlanejamento = $conexao->prepare("INSERT INTO planejamento ($gravarCol) VALUES ($gravarVal)");
			$salvarPlanejamento->execute();
			echo "<br>Cadastrado com sucesso";
			echo "<meta http-equiv='refresh' content='0'>";
		}
		?>
	</div>
</div>
</body>
</html>