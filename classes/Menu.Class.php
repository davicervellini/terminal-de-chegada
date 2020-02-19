<?php

class Menu{

	private $links = array();

	public function append($nomeLink, $href, $from = "", $ident = false){
		
		if(@$_SESSION["usuario"]["usuAdmin"] == 1 || $from == "" || @$_SESSION["permissoes"][$from]['acesso'] == 1){

			$vLinks = array(
				'nomeLink' => $nomeLink,
				'href' => $href,
				'ident' => $ident);

			array_push($this->links, $vLinks);

			if($ident == true){
				$vTitulo = $this->links[count($this->links)-2];

				if(isset($vTitulo["title"])){
					$this->subtitulo = $vTitulo["title"];
				}

				$this->links[count($this->links)-1]["subtitulo"] = $this->subtitulo ;
			}
		}
	}

	public function appendTitle($title){
		array_push($this->links, array(
			"title" => $title
		));
	}

	public function render(){

		if(count($this->links) > 0 ){
			print "
			<li class=\"no-padding\">
				<ul class=\"collapsible\" data-collapsible=\"expandable\">
				  <li>
				    <span class=\"collapsible-header\" style='border:1px solid #FAFAFA;'><i class=\"material-icons\" style=\"color:rgba(0, 0, 0, 0.54);\">".$this->materialIcon."</i>".$this->nomeMenu."<i class=\"material-icons right no-margin\">arrow_drop_down</i></span>
				    <div class=\"collapsible-body no-padding\">
						<ul>";

			foreach ($this->links as $key => $value) {
				if(isset($value["title"])){

					$iCont = 0;
					foreach ($this->links as $chave => $valor) {
						if(isset($valor["subtitulo"]) && $valor["subtitulo"] === $value["title"]){
							$iCont++;
						}
					}
					if($iCont > 0)
						print "<li style='line-height:32px;padding-left:38px;background-color:#FFF;font-weight:bold;'>".$value['title']."</li>";

				}
				else{
					$padding = "padding-left:32px";
					if($value["ident"] == true){
						$padding = "padding-left:56px";
					}
					print "<li class=\"tooltipped\" ><a href=\"".$value['href']."\" style='$padding'><i class=\"material-icons\" style='margin:0;line-height:35px; height:35px; '>keyboard_arrow_right</i>&nbsp;".$value['nomeLink']."</a></li>";
				}
			}

			print "
						</ul>
				    </div>
				  </li>
				</ul>
			</li>

			";
		}
	}

	public function renderLink($nomeLink, $href, $materialIcon, $from = "", $function = ""){
		if($_SESSION["usuario"]["usuAdmin"] == 1 || $_SESSION["permissoes"][$from]['acesso'] == 1){
			if($function == 1){
				print "<li><a onclick=Route.open(\"$href\") style='cursor: pointer;'><i class=\"material-icons\">$materialIcon</i>$nomeLink</a></li>";
			}else{
				print "<li><a href=\"$href\"><i class=\"material-icons\">$materialIcon</i>$nomeLink</a></li>";
			}
		}
	}

	public function __construct($nomeMenu = null, $materialIcon = 'menu'){
		if($nomeMenu !== null){
			$this->nomeMenu     = $nomeMenu;
			$this->materialIcon = $materialIcon;
		}
	}
}
