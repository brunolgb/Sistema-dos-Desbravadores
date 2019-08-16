<?php
session_start();

// buscando sessão
$id = $_SESSION["id"];
$conselheiro = $_SESSION["conselheiro"];
$classe = $_SESSION["classe"];
$sexo = $_SESSION["sexo"];

include_once("conexao.php"); //conexão

$idReq = $conexao->prepare("SELECT requisito FROM planejamento where sexo='".$sexo."'");
$idReq->execute();
$idRequisitos = $idReq->fetchAll(PDO::FETCH_ASSOC);

foreach ($idRequisitos as $buscaReq)
{
	foreach ($buscaReq as $idRequi => $valorRequi)
	{
		$guardaSQL .= " AND idCartao <> '".$valorRequi."'";
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
		<h1 class="titulo">Prenchendo Planejamento do clube</h1>
		<form method="post" action="preenchendo_clube.php">

			<div>
				<label for="atividade">Atividade</label>
				<input type="text" name="atividade" id="atividade" required="">
			</div>
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
				<label for="quem">Quem</label>
				<input type="text" name="quem" id="quem" required="">
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
			foreach ($_POST as $c => $v)
			{
				if ($c != "enviar")
				{
					if($c === "termino")
					{
						$v = empty($v) ? $_POST["quando"] : $v;
					}

					$v = $c === "valor" ? str_replace(",", ".",$v) : $v;
					$gravarCol .= $c.", ";
					$gravarVal .= "'".$v."', ";
				}
			}
			$gravarCol .= "cadastro, usuario";
			$gravarVal .= "'".$cadastro."','".$id."'";
			echo $gravarCol."<br>".$gravarVal;

			$salvarPlanejamento = $conexao->prepare("INSERT INTO planejamento_clube ($gravarCol) VALUES ($gravarVal)");
			$salvarPlanejamento->execute();
			echo "<br>Cadastrado com sucesso";
		}
		?>
	</div>
</div>
</body>
</html>