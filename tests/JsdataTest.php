<?php


namespace W7\Tests;


use Illuminate\Support\Collection;
use W7\App\Model\Logic\AdsLogic;
use W7\App\Model\Logic\StatLogic;

class JsdataTest extends TestCase
{
	/**
	 * 推荐重复bug测试
	 */
	public function testRecommend() {
		/**
		 * @var $app Collection
		 */
		$app = StatLogic::instance()->getRecommentApp();
		$this->assertTrue($app instanceof Collection);

		$uniqueApp = $app->unique(function($item){
			return $item->name;
		});
		$this->assertTrue($app->count() === $uniqueApp->count());
	}

	/**
	 *  测试广告
	 */
	public function testAds() {
		$ads = AdsLogic::instance()->getTopAds();
		$positions = $ads->pluck('position')->unique()->toArray();
		$this->assertIsArray($positions);
		if (count($positions) > 0) {
			$this->assertCount(1, $positions);
		}
	}

	/**
	 * 测试所有广告
	 */
	public function testAllAds() {
		$ads = AdsLogic::instance()->getAllAds();
		$ads->each(function($item){
			$this->assertTrue(isset($item->position));
		});

	}
}
