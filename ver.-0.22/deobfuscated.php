<?php  require_once "seguridad/sql_inject.php"; $bDestroy_session = TRUE; $url_redirect = 'index.php'; $sqlinject = new sql_inject('./log_file_sql.log',$bDestroy_session,$url_redirect); session_start(); error_reporting(0); include_once('./kev/pdo.php'); if(isset($_SESSION['User'])) { $User = $_SESSION['User']; try { $stmt = $con->prepare("SELECT * FROM usuarios WHERE Username = :usuario"); $stmt->bindParam(':usuario', $User, PDO::PARAM_STR); $stmt->execute(); while($datos = $stmt->fetch()) { $idplayer = $datos['ID']; $nombre = $datos['Username']; $Banco = $datos['Banco']; } } catch(PDOException $e) { echo 'Error: ' . $e->getMessage(); } $NumeroBanco = "CTA 1211-1311-00$idplayer"; } else echo "<script>window.location='ingresar.php';</script>"; ?>

<div id="ui-tabs-4" class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-live="polite" aria-labelledby="ui-id-5" role="tabpanel" aria-expanded="true" aria-hidden="false" style="display: block;"><br><table width="98%" cellspacing="1" cellpadding="6" border="0" bgcolor="#C7C7C7" align="center">
<tbody>
<tr bgcolor="#F0F0F0">
<td align="left" style="border-top: 1px solid #FFFFFF;border-left: 1px solid #FFFFFF;">
<strong><font size="2px">Saldo en cuenta bancaria</strong> (<i><?php echo $NumeroBanco; ?></i>)
</td>
</tr>
<tr>
<td valign="middle" bgcolor="#FFFFFF" align="center" colspan="2"><font style="font-size: xx-large; font-size: 35px; font-weight: 600;" color="#009900" size="+3">$<?php echo number_format($Banco, 0, '', '.');?></font></td>
</tr>
</tbody>
</table>
<br><table width="98%" cellspacing="1" cellpadding="6" border="0" bgcolor="#C7C7C7" align="center">
<tbody>
<tr bgcolor="#F0F0F0"><td align="left" style="border-top: 1px solid #FFFFFF;border-left: 1px solid #FFFFFF;" colspan="4"><strong><font size="2px">Movimientos de cuenta</strong></td></tr>
<tr>
<td valign="middle" bgcolor="#FFFFFF" align="center"><font size="2px">Fecha</td>
<td valign="middle" bgcolor="#FFFFFF" align="center"><font size="2px">DE</td>
<td valign="middle" bgcolor="#FFFFFF" align="center"><font size="2px">A</td>
<td valign="middle" bgcolor="#FFFFFF" align="center"><font size="2px">Movimiento</td>

<?php  $stmt = $con->prepare("SELECT * FROM log_transacciones WHERE Enviador = :usuario  OR Receptor = :usuario"); $stmt->bindParam(':usuario', $User, PDO::PARAM_STR); $stmt->execute(); $num_rows = $stmt->rowCount(); if($num_rows >= 1) { while($datos = $stmt->fetch()) { $Fecha = $datos['Fecha']; $Enviador = $datos['Enviador']; $Receptor = $datos['Receptor']; $Monto = $datos['Monto']; ?>

<tr>
<td valign="middle" bgcolor="#FFFFFF" align="center"><font size="2px"><?php echo $Fecha; ?></td>

<?php  if($nombre == $Enviador) { $mensaje = $NumeroBanco; $mensaje2 = "<font color="."#CC0000".">-$$Monto"; }else{ $mensaje = $Enviador; $mensaje2 = "<font color="."#009900".">$$Monto"; } ?>

<td valign="middle" bgcolor="#FFFFFF" align="center"><font size="2px"><?php echo $mensaje;?></td>

<?php  if($nombre == $Receptor) { $mensaje3 = $NumeroBanco; }else{ $mensaje3 = $Receptor; } ?>

<td valign="middle" bgcolor="#FFFFFF" align="center"><font size="2px"><?php echo $mensaje3;?></td>

<td valign="middle" bgcolor="#FFFFFF" align="center"><font size="2px"><?php echo $mensaje2;?></td>

</tr>

<?php  } } else { ?>
		<tr><td valign="middle" bgcolor="#FFFFFF" colspan="4"><font size="2px">No hay registros</td></tr>
<?php  } ?>

</tbody>
</table></div>