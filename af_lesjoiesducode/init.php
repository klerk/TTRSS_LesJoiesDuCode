<?php
class Af_LesJoiesDuCode extends Plugin {
        private $host;

        function init($host) {
			$this->host = $host;
			$host->add_hook($host::HOOK_ARTICLE_FILTER, $this);
        }

        function about() {
			// version, name, description, author, is_system
			return array(1.1, "affiche le gif des articles du site les joies du code", "klerk");
        }

		function hook_article_filter($article) {
			$owner_uid = $article["owner_uid"];
			
			if(strpos($article["link"],"lesjoiesducode.fr/post")!== FALSE || 
			   strpos($article["link"],"lesjoiesducode.tumblr.com/post")!== FALSE){
				
				if(strpos($article["plugin_data"], "af_lesjoiesducode,$owner_uid:") === FALSE) {
                    $html = file_get_html($article["link"]);
                    $element = $html->find('div.bodytype', 0);
                    
                    $images = $temp->find('p.e img');
                    foreach($images as $image){
                        $img_src = str_replace(".jpg",".gif",$image->src);
                        $image->src = $img_src;
                    }
                    
                    $article['content'] = $element->innertext;

				} else if (isset($article["stored"]["content"])) {
					$article["content"] = $article["stored"]["content"];
				}
			}
			
			return $article;
		}
		
        function api_version() {
                return 2;
        }
}
?>
