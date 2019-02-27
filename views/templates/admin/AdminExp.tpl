
<style>
#mailbox,#mailbox2{
	display:none;
	background-color:#CDCDCD;
	position:absolute;
	position: fixed;
    bottom: 0;
    right: 10px;
	width:500px;
	
	z-index:10000;
	
}
#mailbox>.header,#mailbox2>.header{
	background-color:#404040;
	height:30px;
	
}
#mailbox>.body,#mailbox2>.body{
	padding:10px;
	height:500px;
	overflow-y: scroll;
}

#mailbox>.footer,#mailbox2>.footer{
	
	height:10px;
}

</style>

{If $action=="list"}
	<script>
	var contr_link = "{$contr_link}",
		loadingdiv="#loadingdiv",
		item_per_page="{$item_per_page}",
		countexp="{$countexp}";
	$(document).ready(function(){
		init();
	});
	</script>
	<div class="panel">
		<div class="panel-heading">
			{l s='Liste des expéditions'}
		</div>


	<div class="panel-body">
		<div id="loadingdiv" style="height:200px;width:100%;display: none; flex-direction: column; justify-content: center; align-items: center;">
			<img src="{$base_url}images/loading.gif" />
		</div>
		<div id="noexport" style="display:none;width:100%;height:200px;flex-direction: column; justify-content: center; align-items: center;color:gray;font-size:20px;">{l s='Aucune expédition trouvée!'}</div>
		<table id="main1" class="table js-dynamitable  js-dynamitablejs-dynamitablejs-dynamitable "  style="display:none;background-color:#F1F1F1;width:100%;">
			<thead>
			  <tr>
				<th  class="text-center" style="width:120px;"><b>{l s='ID'}</b><span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
				<th style="width:200px;"  class="text-center"><b>{l s='DATE/HEURE DE CREATION'}</b> <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
				<th style="width:200px;"  class="text-center"><b>{l s='MAIL AMANA ENVOYE'}</b> <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
				<th style="width:200px;"  class="text-center"><b>{l s='NOMBRE DES COMMANDES'}</b> <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
				<th style="width:200px;"  class="text-center"><b>{l s='NOMBRE DES COLIS'}</b> <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>
				<!--<th style="width:200px;"  class="text-center"><b>{l s='MAILS CLIENTS ENVOYES'}</b> <span class="js-sorter-desc     glyphicon glyphicon-chevron-down pull-right"></span> <span class="js-sorter-asc     glyphicon glyphicon-chevron-up pull-right"></span> </th>-->
				<th> </th>
			  </tr>
			  <tr>
				  <th> <!-- input filter -->
					
					<input  class="js-filter  form-control" type="text" value="">
				  </th>
				  <th> <!-- input filter -->
					
					<input  class="js-filter  form-control" type="text" value="">
				  </th>
				  <th> <!-- input filter -->
					
					<input  class="js-filter  form-control" type="text" value="">
				  </th>
				  <th> <!-- input filter -->
					
					<input  class="js-filter  form-control" type="text" value="">
				  </th>
				  <th> <!-- input filter -->
					
					<input  class="js-filter  form-control" type="text" value="">
				  </th>
				  <th></th>
				</tr>
			</thead>
			<tbody id="tbody">
			
			</tbody>
		</table>
		<div id="paging" style="text-align:center;"></div>
		
	</div>
	<div class="panel-footer clearfix">
			<div class="pull-right">
			  <a href="{$newlink}" class="btn btn-default">Créer</a>
			</div>
	</div>
	
</div>
{/If}
{If $action=="detail"}
	<div class="panel">
		<div class="panel-heading ">
			{l s='Détails'}
			
		</div>
		<div class="panel-body">
			<div >
			{If isset($export)}
			{l s='Expédition'}  N <b>{$export[0]['id_exp']} - {$export[0]['date_exp']|date_format:"%e/%m/%Y %H:%M"} | NOMBRE DES COMMANDES : <span class="badge">{$export[0]['nbr_orders']}</span>| NOMBRE DES COLIS : <span class="badge">{$export[0]['nbr_colis']}</span> </b>
			{ELSE}
			NAN
			{/If}
			</div>
			<br/><br/>
			<table class="table js-dynamitable  js-dynamitablejs-dynamitablejs-dynamitable " style="background-color:#F1F1F1;width:100%;">
			<thead>
			  <tr>
				<th  class="text-center" style="width:100px;"><b>{l s='ID'}</b></th>
				<th class="text-center"  style="width:120px;"><b>{l s='REFERENCE'}</b></th>
				<th class="text-center" style="width:90px;"><b>{l s='NOMBRE DE COLIS'}</b></th>
				<th class="text-center" style="width:100px;"><b>LC</b></th>
				<th class="text-center" style="width:120px;"><b>{l s='NOM ET PRENOM'}</b></th>
				<th class="text-center" style="width:120px;"><b>{l s='POIDS'}</b></th>
				<th class="text-center" style="width:120px;"><b>{l s='PRIX DE TRANSPORT'}</b></th>
				<th class="text-center" style="width:120px;"><b>{l s='TOTAL HT'}</b></th>
				<th class="text-center" style="width:120px;"><b>{l s='TOTAL HT + TRANSPORT'}</b></th>
				<th class="text-center" style="width:120px;"><b>{l s='TOTAL TTC'}</b></th>
				<th class="text-center" style="width:120px;"><b>{l s='DATE DE CREATION'}</b></th>
				<th class="text-center" style="width:120px;"><b>{l s='PAIMENT'}</b></th>
				<th class="text-center" style="width:120px;"><b>{l s='STATUS'}</b></th>
			  </tr>
			  <tr>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  <th><input  class="js-filter  form-control" type="text" value=""></th>
				  
				</tr>
			</thead>
			<tbody>
			{foreach from=$orders item=foo}
			  <tr>
				<td class="text-center"><span class="badge badge-info">{$foo['id_order']}</span></td>
				<td class="text-center">{$foo['reference']}</td>
				<td class="text-center"><span class="badge badge-success">{$foo['nbr_colis']}</span></td>
				<td class="text-center" ><div style="overflow-x : auto;width:200px;">{$foo['tracking_number']}</div></td>
				<td class="text-center">{$foo['firstname']} {$foo['lastname']}</td>
				<td class="text-center">{$foo['weight']|number_format:2:".":" "} KG</td>
				<td class="text-center">{$foo['shipping']|number_format:2:".":" "}</td>
				<td class="text-center">{$foo['total_ht']|number_format:2:".":" "}</td>
				<td class="text-center">{$foo['total_ht_shipp']|number_format:2:".":" "}</td>
				<td class="text-center">{$foo['total_ttc']|number_format:2:".":" "}</td>
				
				<td class="text-center">{$foo['date_add']|date_format:"%e/%m/%Y %H:%M"}</td>
				<td class="text-center">{$foo['payment']}</td>
				<td class="text-center">{If $foo['is_valid']==0}{l s='ANNULEE'}{ELSE}{l s='ACTIVE'}{/If}</td>
				
			  </tr>
			{/foreach}
			  </tbody>
			</table>
	    </div>
		<div class="panel-footer clearfix">
			<div class="pull-right spaced">
			  <a href="{$finishLink}" class="btn btn-default">{l s='FINIR'}</a>
			</div>
			<!--<div class="pull-right spaced">
			  <a href="{$pdflink}" target="_blank" class="btn btn-default">{l s='BORDEREAU'}</a>
			</div>-->
			<button type="button" class="pull-right spaced btn btn-default {If $nbrtickets==0}disabled{/If}" data-toggle="modal" data-target="#exampleModalLong">
			  {l s='BORDEREAU'}
			</button>
			<div class="pull-right spaced ">
			  <a {If $nbrtickets>0}target="_blank" href="{$ticketlink}" class="btn btn-default"{/If} {If $nbrtickets==0} class="btn empty btn-default disabled"{/If} >{l s='ETIQUETTES'}</a>
			</div>
			<button type="button" class="pull-right spaced btn btn-default {If $nbrtickets==0}disabled{/If}" data-toggle="modal" data-target="#importEnMassePopUp">
			  {l s='IMPORT EN MASSE'}
			</button>
			<!--
			<div class="pull-right spaced">
			  <a id="mailboxbtn2" href="#" class="btn btn-default">{l s='MAILS CLIENTS'}</a>
			</div>-->
			<div class="pull-right spaced">
			  <a id="mailboxbtn" href="#" class="btn btn-default {If $nbrtickets==0}disabled{/If}">{l s='MAIL AMANA'}</a>
			</div>
		</div>
	</div>
	<div id="mailbox">
		<div class="header">
			
			<span class="close"></span>
		</div>
		<div class="body">
			
			<form id="mailform"  method="post" action="{$maillink}" novalidate>
			  <div class="form-group">
				<label for="exampleInputEmail1">À</label>
				<input type="email" name="mails" class="form-control multiplemails" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" value='[{If Configuration::get("amana_mails_159357")}{Configuration::get("amana_mails_159357")}{/If}
				{If Configuration::get("amana_mails_cc_159357")},{Configuration::get("amana_mails_cc_159357")}{/If}]'>
				<!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
			  </div>
			  <input type="hidden" name="id_exp" value="{$export[0]['id_exp']}" />
			  <div class="form-group">
				<label for="exampleInputPassword1">{l s='Objet'}</label>
				<input type="text" name="objet"  class="form-control" id="exampleInputPassword1" value='{$objet}'>
			  </div>
			  
			  <div class="form-group">
				<label for="exampleFormControlTextarea1">Message</label>
				<textarea  name="message"  class="jqte-test form-control" id="exampleFormControlTextarea1" rows="3">
				{$message}
				
				</textarea>
			  </div>
			  <div id="attachments">
				{$n=$num}
				{If $defaultzone>0}
					<input type="hidden" name="impor[0][]" value="{$imporname|replace:'#n':($n+1)}.xls" />
					<input type="hidden" name="impor[1][]" value="-1" />
					<p><input type="checkbox" name="impor[2][]" value="{$xlslink}" checked />{$imporname|replace:'#n':($n+1)}.xls</p>
					
					<input type="hidden" name="bord[0][]" value="{$bordname|replace:'#n':($n+1)}.pdf" />
					<input type="hidden" name="bord[1][]" value="-1" />
					<p><input type="checkbox" name="bord[2][]" value="{$pdflink}" checked />{$bordname|replace:'#n':($n+1)}.pdf</p>
				{/If}
				{$n=$n+1}
				{foreach $zonesList as $z}
					{If $z['nbr']>0}
						<input type="hidden" name="impor[0][]" value="{$imporname|replace:'#n':($n+1)}.xls" />
						<input type="hidden" name="impor[1][]" value="{$z['id']}" />
						<p><input type="checkbox" name="impor[2][]" value="{$xlslink}" checked />{$imporname|replace:'#n':($n+1)}.xls</p>
						
						<input type="hidden" name="bord[0][]" value="{$bordname|replace:'#n':($n+1)}.pdf" />
						<input type="hidden" name="bord[1][]" value="{$z['id']}" />
						<p><input type="checkbox" name="bord[2][]" value="{$pdflink}" checked />{$bordname|replace:'#n':($n+1)}.pdf</p>
					{/If}
					{$n=$n+1}
				{/foreach}
			  </div>
			  
			  <div id="sendConfirm">
			  
			  </div>
			  <button type="submit" class="btn btn-primary">Envoyer</button>
			</form>
		</div>
		<div class="footer"></div>
	</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLongTitle"><b>Commandes par zone</b></h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		
		<!--<h3><a {If $defaultzone>0}target="_blank" href="{$pdflink}&zone_id=-1"{/If} {If $defaultzone==0}class="empty"{/If}>Commandes des autres villes</a><br/></h3>-->
		
        {foreach $zonesList as $z}
			<h3><a {If $z['nbr']>0}target="_blank" href="{$pdflink}&zone_id={$z['id']}"{/If} {If $z['nbr']==0}class="empty"{/If}>{$z['zone_name']}</a><br/></h3>
		{/foreach}
      </div>
      
    </div>
  </div>
</div>

<div class="modal fade" id="importEnMassePopUp" tabindex="-1" role="dialog" aria-labelledby="importEnMassePopUpTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="importEnMassePopUpTitle"><b>Commandes par zone</b></h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		
		<!--<h3><a {If $defaultzone>0}target="_blank" href="{$pdflink}&zone_id=-1"{/If} {If $defaultzone==0}class="empty"{/If}>Commandes des autres villes</a><br/></h3>-->
		
        {foreach $zonesList as $z}
			<h3><a {If $z['nbr']>0}target="_blank" href="{$xlslink}&zone_id={$z['id']}"{/If} {If $z['nbr']==0}class="empty"{/If}>{$z['zone_name']}</a><br/></h3>
		{/foreach}
      </div>
      
    </div>
  </div>
</div>
{/If}




{If $action=="new"}
<script language="Javascript">
var orders_selected=0;
$(document).ready(function(){
	$('.mycheckbox').change(function(){
		var el=$(this).parent().parent().find("input[name='selected[]']");
		if($(this).attr('checked')=="checked"){
			$(el).val('1');
		}else{
			$(el).val('0');
		}
		
		refreshSelected();
	});
	refreshSelected();
	
	$("#form11").submit(function() {
		if(orders_selected==0){
			alert('Aucune commande n\'a été selectionée!')
			return false;
		}
		var c = confirm("La génération des codes à barres sera lancée.\nSouhaitez-vous continuer?");
		return c;
	});
	
	$('#mycheckbox_all').change(function(){
		$("input:checkbox.mycheckbox").prop('checked',this.checked);
		if(this.checked){
			$("input[name='selected[]']").val('1');
		}else{
			$("input[name='selected[]']").val('0');
		}
		refreshSelected();
	});
});
function refreshSelected(){
	//alert($('.mycheckbox:checked').length);
	orders_selected=$('.mycheckbox:checked').length;
	$('#count_selected').text(orders_selected);
}
</script>
	<div class="panel">
		<div class="panel-heading ">
			{l s='Nouvelle expédition'}
		</div>
		{$i=0}
		{foreach from=$orders item=foo}
			{If in_array($foo['id_carrier'], $carriers)}
				{assign var=i value=$i+1}
			{/If}
		{/foreach}
		{If $orders and $i>0}
		<form method="post" id="form11">
		<div class="panel-body">
			
			<br/><br/>
			<table class="table table-condensed table-hover"  style="background-color:#F1F1F1;width:100%;">
			<thead>
			  <tr>
				<th><b><input id="mycheckbox_all" type="checkbox" checked="checked"></b></th>
				<th><b>{l s='ID'}</b></th>
				<th><b>{l s='Référence'}</b></th>
				<th><b>{l s='Nom et Prénom'}</b></th>
				<th><b>{l s='Date de création'}</b></th>
				<th><b>{l s='Nombe de colis'}</b></th>	
			  </tr>
			</thead>
			<tbody>
			{foreach from=$orders item=foo}
				{If in_array($foo['id_carrier'], $carriers)}
				  <tr>
					<td> <input class="mycheckbox" type="checkbox" checked="checked"></td>
					<td>{$foo['id_order']}</td>
					<td>{$foo['reference']}</td>
					<td>{$foo['firstname']} {$foo['lastname']}</td>
					<td>{$foo['date_add']|date_format:"%d/%m/%Y - %H:%M"}</td>
					<input type="hidden" name="id_order[]" value="{$foo['id_order']}" />
					<input type="hidden" name="selected[]" value="1" />
					<td><input style="text-align:right;width:40px;" type="number" class="stepperpositif" name="nbr_colis[]" step="1" value='1'></td>
					
				  </tr>
				  {/If}
			{/foreach}
			  </tbody>
			</table>
	    </div>
		<div class="panel-footer clearfix">
			<div class="text-center">
				<p style="font-weight: bold;"><span id="count_selected">-</span>/{$i}</p>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<input type="submit" name="submitBtn" value='Générer LC' class="btn btn-default" />
			</div>
			<div class="pull-right">
				<a href="{$finishLink}" class="btn btn-default">{l s='Annuler'}</a>
			</div>
		</div>
		</form>
		{ELSE}
		<div class="panel-body">
			<div style="width:100%;height:50px;text-align:center;color:gray;font-size:20px;">{l s='Aucune commande trouvée!'}</div>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<a href="{$finishLink}" class="btn btn-default">{l s='Retour'}</a>
			</div>
		</div>
		{/If}
	</div>
{/If}



<script>
$(document).ready(function(){
	$(document).find(".js-dynamitable").each(
	function(){
		$(this).dynamitable().addFilter(".js-filter").addSorter(".js-sorter-asc","asc").addSorter(".js-sorter-desc","desc");
	}
	);
});
</script>
