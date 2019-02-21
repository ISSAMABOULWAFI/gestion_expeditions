<script>
$(document).ready(function(){
	$('#sel1').change(function(){
		window.location.href = '{$adminMagLink}&id_mag='+ $(this).val();
	});
	$('#zone_sel').change(function(){
		window.location.href = '{$adminMagLink}&id_zone='+ $(this).val();
	});
	$('#addZone').click(function(){
		//alert($('#new_zone').val());
		$.ajax( "{$adminMagLink}&action=add&zone_name="+$('#new_zone').val() )
		  .done(function() {
			 location.reload();
		  })
		  .fail(function() {
			alert( "error" );
		  });
	});
	
	$('#addCode').click(function(){
		//alert($('#new_zone').val());
		$.ajax( "{$adminMagLink}&action=addCode&libelle="+$('#new_libelle').val()+"&code="+$('#new_code').val() )
		  .done(function() {
			 location.reload();
		  })
		  .fail(function() {
			alert( "error" );
		  });
	});
	$('.removeCode').click(function(){
		if (confirm("Voulez vous supprimer?")) {
			//alert("ok"+$(this).attr('data'));
			$.ajax( "{$adminMagLink}&action=remove&libelle="+$(this).attr('data'))
			  .done(function() {
				 location.href="{$adminMagLink}";
			  })
			  .fail(function() {
				alert( "Error" );
			  });
		}
		
	});
	$('#removeZone').click(function(){
		//alert($('#zone_sel').val());

		if (confirm("Voulez vous supprimer?")) {
			$.ajax( "{$adminMagLink}&action=remove&zone_name="+$('#zone_sel').val() )
			  .done(function() {
				 location.href="{$adminMagLink}";
			  })
			  .fail(function() {
				alert( "error" );
			  });
			  
		}
		
	});
	
});
</script>

{IF $action=='main'}
<div class="panel">
  
    <div class="panel-heading"><i class="icon-cogs"></i> {l s='GESTION DES MAGASIN - CODES POSTAUX'}</div>
  <form method="post">
  <div class="panel-body">
		
      <div style="width:100%;">
		<div class="form-group">
		  <label for="sel1">{l s='Magasin :'}</label>
		  <select class="form-control" name="id_mag" id="sel1">
		  {IF $listMagasin}
			{FOREACH $listMagasin as $m}
				<option value="{$m['id_store']}" {IF isset($smarty.get.id_mag) && $m['id_store']== $smarty.get.id_mag}selected{/IF}>{$m['name']} - {$m['address1']} {$m['city']}</option>
			{/FOREACH}
		  {/IF}
		  </select>
                  
		</div>
		
	  </div>
	 <div class="form-group">     
			<label for="postcode_0" class="control-label col-lg-3">
				<span data-html="true" data-original-title="Séparez vos codes postaux avec des virgules comme suit: 75001,75002,<br><br>Vous pouvez utiliser tout type de format de codes postaux tant que ceux-ci sont séparés par des virgules." class="label-tooltip" data-toggle="tooltip" title="">pour les codes postaux suivants
				</span>
			</label>	 
			<input type="hidden" name="id_cd_in" value="{IF $cdPList}{$cdPList[0]['id_cd']}{/IF}" />
			<input type="text" name="cd_in" value="{IF $cdPList}{$cdPList[0]['from_']}{/IF}" data-role="tagsinput" />
			
       </div>
	   
	   <div class="form-group">     
			<label for="postcode_1" class="control-label col-lg-3">
                        <span data-html="true" data-original-title="Séparez vos codes postaux avec des virgules comme suit: 750,750," class="label-tooltip" data-toggle="tooltip" title="">ou les codes postaux commençant par
                        </span>
                    </label>
			<input type="hidden" name="id_cd_begin" value="{IF $cdPBegin}{$cdPBegin[0]['id_cd']}{/IF}" />					
			<input type="text" name="cd_begin" value="{IF $cdPBegin}{$cdPBegin[0]['from_']}{/IF}" data-role="tagsinput" />           
       </div>
	   <div class="form-group"> 
			<label for="postcode_range_from_0" class="control-label col-lg-3">
				<span data-html="true" data-original-title="Cette option ne fonctionne que pour les codes postaux numériques" class="label-tooltip" data-toggle="tooltip" title="">ou les codes postaux entre
				</span>
			</label>
		   <div class="postdeliv col-lg-9 myclass1">
				{IF $cdPBetween}
					{FOREACH $cdPBetween as $row}
						<div class="postal_range">
							<input type="hidden" name="id_cd_bet[]" value="{$row['id_cd']}" />	
							<input type="text" name="cd_bet_from[]" value="{$row['from_']}" class="inline fixed-width-lg"> -  
							<input type="text" name="cd_bet_to[]" value="{$row['to_']}" class="inline fixed-width-lg">
							<i class="icon-minus-sign"></i>
						</div>
					{/FOREACH}
				{/IF}
				<div class="postal_range">
					<input type="hidden" name="id_cd_bet[]" value="" />	
					<input type="text" name="cd_bet_from[]" class="inline fixed-width-lg"> -  
					<input type="text" name="cd_bet_to[]" class="inline fixed-width-lg">
					<i class="icon-plus-sign"></i>
				</div>
				<input id="to_delete" type="hidden" name="to_delete" value="" />
			</div>
		</div>
		<div class="form-group"> 
			<label for="postcode_range_from_0" class="control-label col-lg-3">
				<span data-html="true" data-original-title="Cette option ne fonctionne que pour les codes postaux numériques" class="label-tooltip" data-toggle="tooltip" title="">{l s='Retour magasin'}
				</span>
			</label>
		   <div class="form-check form-check-inline">
			  <input class="form-check-input"  name="is_active" type="checkbox" id="inlineCheckbox1" {IF $is_active}checked{/IF}>
			</div>
		</div>
		
		<div class="form-group"> 
			<label for="postcode_range_from_0" class="control-label col-lg-3">
				<span data-html="true" data-original-title="Cette option ne fonctionne que pour les codes postaux numériques" class="label-tooltip" data-toggle="tooltip" title="">{l s='Magasin des retours par défaut'}
				</span>
			</label>
		   <div class="form-check form-check-inline">
			  <input class="form-check-input" name="is_default" type="checkbox" id="inlineCheckbox2" {IF $is_default}checked{/IF} {IF !$is_active}disabled{/IF}>
			</div>
		</div>
		
  </div>
  <div class="panel-footer clearfix">
    <div class="pull-right">
      
	    <button type="submit" name="submitbtn" id="submitMessage"
			class="button btn btn-default button-medium">
		  <span>{l s='Save'} <i class="icon-save right"></i></span>
		</button>
    </div>
  </div>
  </form>
</div>


<div class="panel">
  
    <div class="panel-heading"><i class="icon-cogs"></i> {l s='LES TRANSPORTEURS CONCERNES'}</div>
  <form method="post">
  <div class="panel-body">
		{IF isset($carriers)}
			
			<pre>
			{FOREACH $carriers as $c}
				
				<input type="checkbox" name="carrier[]" value="{$c['id_carrier']}" {($c['enabled']=='true')?'checked':''}/>{($c['name']=='0')?'DEFAULT':$c['name']}<br/>
			{/FOREACH}
			</pre>
		{/IF}
      
	   
		
  </div>
  <div class="panel-footer clearfix">
    <div class="pull-right">
      
	    <button type="submit" name="submitbtn2" id="submitMessage"
			class="button btn btn-default button-medium">
		  <span>{l s='Save'} <i class="icon-save right"></i></span>
		</button>
    </div>
  </div>
  </form>
</div>

<div class="panel">
  
    <div class="panel-heading"><i class="icon-cogs"></i> {l s='GESTION DES ZONES SPECIFIQUES AMANA'}</div>
  <form method="post">
  <div class="panel-body">
		<div class="form-group">     
			<label for="zip_cd_zone" class="control-label col-lg-3">
				<span data-html="true"  class="label-tooltip" data-toggle="tooltip" title="">Nouveau
				</span>
			</label>	 
			<input type="text" id="new_zone" name="new_zone" value=""  />
			<div id="addZone"
				class="button btn btn-default button-medium">
			  <span>{l s='Ajouter'} <i class="icon-plus right"></i></span>
			</div>
       </div>
	   
		<select id="zone_sel" name="zone_id">
			
		
		{IF isset($zones)}
			{FOREACH $zones as $zone1}
				<option value="{$zone1['id']}" {IF isset($smarty.get.id_zone) && $zone1['id']== $smarty.get.id_zone}selected{/IF}>{$zone1['zone_name']}</option>
			{/FOREACH}
		{/IF}
		</select>
		
		<div class="form-group">     
			<label for="zip_cd_zone" class="control-label col-lg-3">
				<span data-html="true" data-original-title="Séparez vos codes postaux avec des virgules comme suit: 75001,75002,<br><br>Vous pouvez utiliser tout type de format de codes postaux tant que ceux-ci sont séparés par des virgules." class="label-tooltip" data-toggle="tooltip" title="">pour les codes postaux suivants
				</span>
			</label>	 
			<input type="text" name="zip_cd_zone" value="{$cdp}" data-role="tagsinput" />
			
       </div>
	   <div class="form-group"> 
			<label for="postcode_range_from_0" class="control-label col-lg-3">
				<span data-html="true" data-original-title="Cette option ne fonctionne que pour les codes postaux numériques" class="label-tooltip" data-toggle="tooltip" title="">ou les codes postaux entre
				</span>
			</label>
			
		   <div class="postdeliv col-lg-9 myclass2">
				{IF $zoneBetween}
					{FOREACH $zoneBetween as $elem}
						<div class="postal_range">
							<input type="hidden" name="id_cd_bet[]" value="{$elem['id']}" />	
							<input type="text" name="cd_bet_from[]" value="{$elem['from_']}" class="inline fixed-width-lg"> -  
							<input type="text" name="cd_bet_to[]" value="{$elem['to_']}" class="inline fixed-width-lg">
							<i class="icon-minus-sign"></i>
						</div>
					{/FOREACH}
				{/IF}
				<div class="postal_range">
					<input type="hidden" name="id_cd_bet[]" value="" />	
					<input type="text" name="cd_bet_from[]" class="inline fixed-width-lg"> -  
					<input type="text" name="cd_bet_to[]" class="inline fixed-width-lg">
					<i class="icon-plus-sign"></i>
				</div>
				<input id="to_delete2" type="hidden" name="to_delete2" value="" />
			</div>
		</div>
		
		<div id="removeZone"
				class="button btn btn-default button-medium">
			  <span>{l s='Supprimer'} <i class="icon-minus right"></i></span>
			</div>
  </div>
  <div class="panel-footer clearfix">
    <div class="pull-right">
      
	    <button type="submit" name="submitbtn3" id="submitMessage"
			class="button btn btn-default button-medium">
		  <span>{l s='Save'} <i class="icon-save right"></i></span>
		</button>
    </div>
  </div>
  </form>
</div>






<div class="panel">
  
    <div class="panel-heading"><i class="icon-cogs"></i> {l s='GESTION DES CODES POSTAUX AMANA'}</div>
  <form method="post">
  <div class="panel-body">
		
      <div style="width:100%;">
		<div class="form-group">
		  <!--<label for="sel1"></label>-->
		  <div style="height:300px;overflow-y: scroll;">
		  <table  class="table table-condensed table-hover">
				<thead>
				<tr><th>{l s="Ville"}</th><th>{l s="Code Amana"}</th><th>{l s="Action"}</th></tr>
				</thead>
				<tbody>
				{FOREACH $amanacodes as $el}
					<tr><td>{$el['libelle']}</td><td>{$el['cd']}</td>
					
					<td>
					
					<button type="button" class="removeCode" data="{$el['libelle']}" 
						class="button btn btn-default button-medium">
					  <span>{l s='Supprimer'} <i class="icon-remove right"></i></span>
					</button>
					
					</td>
					</tr>
				{/FOREACH}
				</tbody>
		  </table>
		  
		  </div>
		</div>
		
		<div class="form-group">
		  <label for="sel1">{l s="Nouveau"}</label>
		  <div>
			<pre>
			{l s="Libellé"}<input type="text" name="" id="new_libelle">
			{l s="Code Amana"}<input type="text" name="" id="new_code">
			<input type="button" name="" value="Ajouter" id="addCode">
			
			</pre>
		  </div>
		</div>
	  </div>
  </div>
  
  </form>
</div>
{/IF}

{IF $action=='validate'}

{/IF}