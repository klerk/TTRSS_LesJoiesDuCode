<?php
class Af_LesJoiesDuCode extends Plugin {
        private $host;

        function init($host) {
			$this->host = $host;
			$host->add_hook($host::HOOK_ARTICLE_FILTER, $this);
        }

        function about() {
			// version, name, description, author, is_system
			return array(1.0, "affiche le gif des articles du site les joies du code", "klerk");
        }

		function hook_article_filter($article) {
			$owner_uid = $article["owner_uid"];
			
			if(strpos($article["link"],"lesjoiesducode.fr/post")!== FALSE || 
			   strpos($article["link"],"lesjoiesducode.tumblr.com/post")!== FALSE){
				
				if(strpos($article["plugin_data"], "af_lesjoiesducode,$owner_uid:") === FALSE) {
					$doc = new DOMDocument();
					@$doc->loadHTML(fetch_file_contents($article["link"]));

					$basenode = false;

					if ($doc) {
						$xpath = new DOMXPath($doc);
						$basenode = $xpath->query('//div[@class="bodytype"]')->item(0);

						if ($basenode) {
							$article["content"] = $doc->saveXML($basenode);
							$article["plugin_data"] = "af_lesjoiesducode,$owner_uid:" . $article["plugin_data"];
						}
					}
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