<?php
class CuSecurityPatche extends AppModel{

    public function getSecurityPatchInfo(){
        App::uses('File', 'Utility');

        $url = Configure::read('CuSecurityPatch.source_url');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $_json = curl_exec($ch);
        curl_close($ch);

        $json = mb_convert_encoding($_json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $decodedJson = json_decode($json, true);
		$coreVersion = getVersion();

        foreach($decodedJson['articles'] as $article){
			$patchExist = $this->find('first', [
				'conditions' => [
					'CuSecurityPatche.url' => $article['url'],
				]
			]);

            // 本baserのバージョンより古いバージョンに対する記事はdoneを1にする
            if(empty($patchExist)){
				$data = [
					'CuSecurityPatche' => [
						'title' => $article['title'],
						'version' => $article['version'],
						'url' => $article['url'],
						'publish_date' => $article['publish_date'],
						'done' => $this->isDane($coreVersion, $article['version']) ? 1 : 0,
					]
				];
            } else {
				$data = $patchExist;
				$data['CuSecurityPatche']['title'] = $article['title'];
				$data['CuSecurityPatche']['version'] = $article['version'];
				$data['CuSecurityPatche']['publish_date'] = $article['publish_date'];
            }
			$this->create();
			$this->save($data);
        }
    }

	private function isDane($myVersion, $targetVersion) {
		if (version_compare($myVersion, $targetVersion) == -1) {
			return false;
		}
		return true;
	}
}
