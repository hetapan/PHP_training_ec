<?php
require_once('common/util.inc.php');
require_once('class/Model.php');

session_start();

//Productクラスの商品一覧取得を行う
$product = new Product;
$products = $product->getProductList($sort = 'turn', $order = 'ASC');
?>
<?php require_once('header.php') ?>
<div id="wrap">
    <article id="main_contents">
        <section id="first_view">
            <img src="images/first_view_02.jpg" alt="ふわふわだけど高級感のあるカシミアセーター">
            <div class="first_view_text">通常のカシミアよりも4倍暖かくあなたを包んでくれます‼<br> 毛糸の細さ、カラー、デザインを選べて、何百通りからあなたの好みのカシミアセーターに・・・</div>
            <div class="sankaku"></div>
            <div class="purchase_btn01">
                <div class="reflection-img"><a href="cart.php"><img src="images/purchase_btn01.png" alt="ご購入はこちら"></a>
                    <div class="reflection"></div>
                </div>
            </div>
        </section>
        <section id="first_contents">
            <div class="osusume">
                <h1><span id="green">HuwaHuwaのカシミアセーター</span>は<br>こんな方にオススメです!!</h1>
                <ul>
                    <li><span id="orenge">本物のカシミア</span>のセーターで大人の着こなしをしたい!!</li>
                    <li><span id="orenge">毎年、着こなせる</span>お気に入りの一着を探している。</li>
                    <li><span id="orenge">肌ざわりが良く、ストレスなく</span>綺麗に着こなせるインナーが欲しい。</li>
                    <ul>
            </div>
            <div class="sozai">
                <h1>暖かいだけでなく、豪華なイメージにもこだわりました！</h1>
                <hr style="width:80%;">
                <div class="sozai_subtitle"><span>ゆったりとしたデザインでスタイルを選びません。</span></div>
                <div class="sozai_setumei">
                    <img src="images/sozai_sp.jpg" alt="カシミアとミンクの特徴">
                    <div class="cashmere_sozai">
                        <p>
                            <span class="brown">【カシミアとは…】</span><br>
                            厳しい自然環境で育まれたカシミヤ山羊から採れる産毛のことで、繊維が細く暖かさかつ軽量さを兼ね備えており、<br>
                            希少価値のある毛になります。
                        </p>
                    </div>
                    <div class="mink_sozai">
                        <p>
                            <span class="brown">【ミンクとは…】</span><br>
                            年中、寒い雪山で生息している動物です。<br>
                            極寒のクリーンな環境の中で育っていますので、完璧な美しい毛になります。<br>
                            ミンクの毛はリッチで、絨毛豊かでふわふわとしており、風を通さず、雪や雨をも弾いてくれます。
                        </p>
                    </div>
                </div>
            </div>
            <div class="mink_cashmere">
                <h2>自然素材で密度が細かく保温性が高いミンクカシミア<br><span class="dark_red">－羊の毛の1.5倍から2倍です－</span></h2>
                <div class="mink_cashmere_setumei">
                    <img src="images/mink_cashmere_sp.png" alt="ヤギとミンク">
                    <span class="light_brown">①濃密度</span><br>
                    <div class="setumei"> ミンクカシミアの繊維は、高い保温性に優れており、<br>とっても断熱性の高い素材で有名です。<br> 他の動物の産毛の中でもナンバーワンの保温性を持っています。 </div>
                    <span class="light_brown">②湿気は通さないけど、空気は通る</span><br>
                    <div class="setumei"> 空気の調節の機能があり、自分の体温に合うように調整してくれて<br>とても着心地がいいです。 </div>
                    <span class="light_brown">③超柔らかくて気持ち良い</span><br>
                    <div class="setumei"> 「国際標準５Ａ」認定!!　チクチク感が少なく保温性に優れた高品質。<br> 毛が細かく、柔らかく滑らかな肌触りが気持ちいいです。 </div>
                </div>
            </div>
        </section>
        <section id="second_contents">
            <div class="second_contents_title01">
                <h1>恋に落ちてしまいそうな肌ざわり、一度着たらやみつきに！！</h1>
            </div>
            <ul class="second_contents_left">
                <li><img src="images/image1_01.jpg"></li>
                <li><img src="images/image1_02.jpg"></li>
                <li class="second_contents_text01">毎日でも着まわしたくなるような<br> 着心地のよさと暖かさ。<br><br> 流行に左右されないデザインや素材で<br> マストアイテムになること間違いなし!!</li>
            </ul>
            <ul class="second_contents_right">
                <li class="second_contents_text02">シンプルに着こなすだけで<br> 大人の雰囲気を醸し出す。<br> クラシックで丸みのあるデザインに<br> ふわふわの素材。</li>
                <li><img src="images/image1_03.jpg"></li>
                <li><img src="images/image1_04.jpg"></li>
            </ul>
            <div class="clear"></div>
            <div class="second_contents_title02">
                <h2>チクチク感が少なく、使った人だけが味わえる本物の風合い</h2>
            </div>
            <img src="images/image2.jpg" alt="チクチク感が少ないセーター">
        </section>
        <section id="third_contents">
            <div class="color_variation">
                <div class="third_contents_title">
                    <h1>豊富なカラーバリエーションで登場</h1>
                </div>
                <img src="images/color_image01_sp.jpg" alt="豊富なカラーのセーター">
                <div class="color_variation_setumei">
                    <p>
                        <span>全45色</span>からお好きなカラーをお選び頂き、
                        さらにデザインにより毛糸の長さを
                        お選び頂くことができます。<br><br>
                        また品質のいい材料を使っておりますので、
                        美しく上品な光沢感を出しており、
                        それぞれの色合いを
                        十分に引き出してくれるミンクカシミア。<br><br>
                        皆様に満足して頂けるセーターに
                        仕上げております。
                    </p>
                </div>
            </div>
            <div class="color_select">
                <h2>豊富なカラーの品揃え!!　<span>全45色</span>からお好みの1色をみつけてください。</h2>
                <hr class="border">
                <p>ベーシックなカラーからコーディネートのアクセントカラーまで取り揃えております。</p>
                <ul class="color_sample">
                    <li><img src="images/color_variation_wh.jpg"></li>
                    <li><img src="images/color_variation_bk.jpg"></li>
                    <li><img src="images/color_variation_gr.jpg"></li>
                    <li><img src="images/color_variation_re.jpg"></li>
                    <li><img src="images/color_variation_ye.jpg"></li>
                </ul>
                <ul class="color_sample_name">
                    <li>ホワイト(キナリ)</li>
                    <li>ブラック</li>
                    <li>ライト杢グレー</li>
                    <li>レッド</li>
                    <li>イエロー</li>
                </ul>
                <ul class="color_sample">
                    <li><img src="images/color_variation_pu.jpg"></li>
                    <li><img src="images/color_variation_rbl.jpg"></li>
                    <li><img src="images/color_variation_be.jpg"></li>
                    <li><img src="images/color_variation_pk.jpg"></li>
                    <li><img src="images/color_variation_lbl.jpg"></li>
                </ul>
                <ul class="color_sample_name">
                    <li>パープル</li>
                    <li>ロイヤルブルー</li>
                    <li>ベージュ</li>
                    <li>ピンク</li>
                    <li>ライトブルー</li>
                </ul>
                <div class="color_sample_attention">※商品により在庫がない場合がございます。各商品ページで在庫のご確認をお願いします。<br> ※ご覧いただいている環境により発色が異なりますので、実際の商品の色と若干異なる場合がございます。</div>
            </div>
            <div class="items">
                <div class="items_title">
                    <h3>重量感がありエレガントな見た目で、ふわふわでなめらかな着心地。</h3>
                </div>
                <div class="item_select">
                    <p>＊デザインにより○○cm～○○cmの中から毛糸の細さをお選びいただけます。</p>
                    <ul>
                        <?php for ($i = 0; $i < count($products); $i++) : ?>
                            <li>
                                <div class="item_select_box">
                                    <img class="item_img" src="<?=IMGS_PATH_TOP?><?=!empty($products[$i]['img']) ? h($products[$i]['img']) : 'no_image.jpg';?>">
                                </div>
                                <div class="purchase_btn02">
                                    <div class="purchase_btn02_box_2">
                                        <p class="item_name"><?=h($products[$i]['name'])?></p>
                                        <p class="item_price"><?=number_format(h($products[$i]['price']))?>円</p>
                                        <p class="item_text"><?=nl2br(h($products[$i]['description']))?></p>
                                    </div>
                                    <div class="purchase_btn02_box_3">
                                        <form action="cart.php" method="post">
                                            <input type="hidden" name="product_id" value="<?=$products[$i]['id']?>">
                                            <p><input type="submit" name="add_cart_btn" value=" "></p>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <?php if ($i % 4 == 3) : ?>
                                </ul><ul>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>
            <div class="item_size">
                <img src="images/item_size.png" alt="サイズ">
            </div>
            <div class="item_syousai">
                <img src="images/item_syousai.png" alt="商品詳細">
            </div>
            <div id="shopping_guide">
                <ul class="left">
                    <li>送料</li>
                    <li>ギフト</li>
                    <li>発送</li>
                    <li>返品</li>
                    <li>品番</li>
                    <li><span style="margin-right: 5em;"></span></li>
                    <li>商品名</li>
                    <li>使用上の注意</li>
                    <li><span style="margin-right: 5em;"></span></li>
                </ul>
                <ul class="right">
                    <li>無料</li>
                    <li>ギフトラッピング無料</li>
                    <li>発送まで1週間から10日</li>
                    <li>届いて1週間以内にご連絡</li>
                    <li>〇〇〇<br>※お問い合わせの際は、こちらの品番をお伝えください。</li>
                    <li>〇〇〇</li>
                    <li>長時間着ることにより、摩擦で静電気が発生するため<br> 毛玉ができる可能性があります。</li>
                </ul>
                <div class="clear"></div>
            </div>
        </section>
        <p id="back-top">
            <a href="#top"><img src="images/back-top.png" alt="ＴＯＰへ戻る"></a>
        </p>
    </article>
</div>
<?php require_once('footer.php') ?>