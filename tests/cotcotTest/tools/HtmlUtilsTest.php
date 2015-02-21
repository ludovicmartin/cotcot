<?php

namespace cotcotTest\component\web\response\helper;

use \cotcot\tools\HtmlUtils as Html;

class HtmlUtilsTest extends \PHPUnit_Framework_TestCase {

    public function test_renderInput() {
        $f = new \cotcot\component\web\form\Form();
        $f->validators['aaa'] = array(new \cotcot\component\validator\Safe());
        $f->setData(array('aaa' => 'bbb'));

        $this->assertEquals('<input name="aaa" type="text" />', Html::renderInput('aaa'));
        $this->assertEquals('<input name="aaa" type="date" />', Html::renderInput('aaa', 'date'));
        $this->assertEquals('<input name="aaa" type="date" value="bbb" />', Html::renderInput('aaa', 'date', $f));
        $this->assertEquals('<input lang="fr" name="aaa" type="date" value="bbb" />', Html::renderInput('aaa', 'date', $f, array('lang' => 'fr')));
        $this->assertEquals('<input lang="fr" name="aaa" type="file" />', Html::renderInput('aaa', 'file', $f, array('lang' => 'fr')));
    }

    public function test_renderSelect() {
        $f = new \cotcot\component\web\form\Form();
        $f->validators['aaa'] = array(new \cotcot\component\validator\Safe());
        $f->setData(array('aaa' => 'bbb'));

        $this->assertEquals('<select name="aaa"></select>', Html::renderSelect('aaa'));
        $this->assertEquals('<select name="aaa"><option value="bbb">BBB</option></select>', Html::renderSelect('aaa', array('bbb' => 'BBB')));
        $this->assertEquals('<select name="aaa"><option value="bbb" selected="selected">BBB</option></select>', Html::renderSelect('aaa', array('bbb' => 'BBB'), $f));
        $this->assertEquals('<select lang="fr" name="aaa"><option value="bbb" selected="selected">BBB</option></select>', Html::renderSelect('aaa', array('bbb' => 'BBB'), $f, array('lang' => 'fr')));
    }

    public function test_renderCheckableInput() {
        $f = new \cotcot\component\web\form\Form();
        $f->validators['aaa'] = array(new \cotcot\component\validator\Safe());
        $f->setData(array('aaa' => 'bbb'));

        $this->assertEquals('<input name="aaa" value="1" type="checkbox" />', Html::renderCheckableInput('aaa'));
        $this->assertEquals('<input name="aaa" value="1" type="radio" />', Html::renderCheckableInput('aaa', 'radio'));
        $this->assertEquals('<input name="aaa" value="bbb" type="radio" />', Html::renderCheckableInput('aaa', 'radio', 'bbb'));
        $this->assertEquals('<input name="aaa" value="bbb" type="radio" checked="checked" />', Html::renderCheckableInput('aaa', 'radio', 'bbb', $f));
        $this->assertEquals('<input lang="fr" name="aaa" value="bbb" type="radio" checked="checked" />', Html::renderCheckableInput('aaa', 'radio', 'bbb', $f, array('lang' => 'fr')));
    }

    public function test_renderTextarea() {
        $f = new \cotcot\component\web\form\Form();
        $f->validators['aaa'] = array(new \cotcot\component\validator\Safe());
        $f->setData(array('aaa' => 'bbb'));

        $this->assertEquals('<textarea name="aaa"></textarea>', Html::renderTextarea('aaa'));
        $this->assertEquals('<textarea name="aaa">bbb</textarea>', Html::renderTextarea('aaa', $f));
        $this->assertEquals('<textarea lang="fr" name="aaa">bbb</textarea>', Html::renderTextarea('aaa', $f, array('lang' => 'fr')));
    }

    public function test_renderImage() {
        $this->assertEquals('<img src="aaa" alt="" />', Html::renderImage('aaa'));
        $this->assertEquals('<img src="aaa" alt="bbb" />', Html::renderImage('aaa', 'bbb'));
        $this->assertEquals('<img lang="fr" src="aaa" alt="bbb" />', Html::renderImage('aaa', 'bbb', array('lang' => 'fr')));
    }

    public function test_formatTagAttributes() {
        $this->assertEquals('aaa="bbb" ccc="ddd"', Html::formatTagAttributes(array('aaa' => 'bbb', 'ccc' => 'ddd')));
    }

    public function test_renderMessageList() {
        $this->assertEquals('<ul ><li>&lt;a&gt;</li><li>b</li></ul>', Html::renderMessageList(array('<a>', 'b')));
        $this->assertEquals('<ul x="y"><li>&lt;a&gt;</li><li>b</li></ul>', Html::renderMessageList(array('<a>', 'b'), array('x' => 'y'), true));
        $this->assertEquals('<ul ><li><a></li><li>b</li></ul>', Html::renderMessageList(array('<a>', 'b'), array(), false));
    }

    public function test_stripTaggedText() {
        $this->assertEquals('salut les amis' . PHP_EOL . 'salut les amis', Html::stripTaggedText('[B]salut les amis[/B]' . PHP_EOL . '[I=abcd]salut les amis[/I]'));
    }

    public function test_renderTaggedText() {
        $callback = function($tagName, $tagParams) {
            switch ($tagName) {
                case 'A':
                    return 'B';
                    break;
                case 'B':
                    return 'C:' . $tagParams;
                    break;
                case '/C':
                    return 'D';
                    break;
            }
            return null;
        };

        $this->assertEquals('&lt;test=&quot;aaa&quot;&gt;', Html::renderTaggedText('<test="aaa">', $callback));
        $this->assertEquals('salut les amis', Html::renderTaggedText('salut les amis', $callback));
        $this->assertEquals('B', Html::renderTaggedText('[A]', $callback));
        $this->assertEquals('D', Html::renderTaggedText('[/C]', $callback));
        $this->assertEquals('salut Bles amis', Html::renderTaggedText('salut [A]les amis', $callback));
        $this->assertEquals('salut<br />' . PHP_EOL . 'les amis', Html::renderTaggedText('salut' . PHP_EOL . 'les amis', $callback));
        $this->assertEquals('salut' . PHP_EOL . 'les amis', Html::renderTaggedText('salut' . PHP_EOL . 'les amis', $callback, false));
        $this->assertEquals('C:1234', Html::renderTaggedText('[B=1234]', $callback));
        $this->assertEquals('C:12=34', Html::renderTaggedText('[B=12=34]', $callback));
        $this->assertEquals('Baaa', Html::renderTaggedText('[A]aaa', $callback));
        $this->assertEquals('aaaBaaa', Html::renderTaggedText('aaa[A]aaa', $callback));
        $this->assertEquals('aaaB', Html::renderTaggedText('aaa[A]', $callback));
    }

    public function test_renderCheckableInputList() {
        $this->assertEquals('', Html::renderCheckableInputList('xxx'));
        $this->assertEquals('<label ><input name="xxx" value="aaa" type="radio" />bbb</label><label ><input name="xxx" value="ccc" type="radio" />ddd</label>', Html::renderCheckableInputList('xxx', 'radio', array('aaa' => 'bbb', 'ccc' => 'ddd')));
        $this->assertEquals('<label ><input name="xxx[]" value="aaa" type="checkbox" />bbb</label><label ><input name="xxx[]" value="ccc" type="checkbox" />ddd</label>', Html::renderCheckableInputList('xxx', 'checkbox', array('aaa' => 'bbb', 'ccc' => 'ddd')));

        $f = new \cotcot\component\web\form\Form();
        $f->validators['xxx'] = array(new \cotcot\component\validator\Safe());
        $f->setData(array('xxx' => 'ccc'));
        $this->assertEquals('<label ><input name="xxx" value="aaa" type="radio" />bbb</label><label ><input name="xxx" value="ccc" type="radio" checked="checked" />ddd</label>', Html::renderCheckableInputList('xxx', 'radio', array('aaa' => 'bbb', 'ccc' => 'ddd'), $f));
        $f->setData(array('xxx' => array('ccc')));
        $this->assertEquals('<label ><input name="xxx[]" value="aaa" type="checkbox" />bbb</label><label ><input name="xxx[]" value="ccc" type="checkbox" checked="checked" />ddd</label>', Html::renderCheckableInputList('xxx', 'checkbox', array('aaa' => 'bbb', 'ccc' => 'ddd'), $f));

        $f->setData(array('xxx' => 'ccc'));
        $this->assertEquals('<label lang="fr"><input name="xxx" value="aaa" type="radio" />bbb</label><label lang="fr"><input name="xxx" value="ccc" type="radio" checked="checked" />ddd</label>', Html::renderCheckableInputList('xxx', 'radio', array('aaa' => 'bbb', 'ccc' => 'ddd'), $f, array('lang' => 'fr')));
        $f->setData(array('xxx' => array('ccc')));
        $this->assertEquals('<label lang="fr"><input name="xxx[]" value="aaa" type="checkbox" />bbb</label><label lang="fr"><input name="xxx[]" value="ccc" type="checkbox" checked="checked" />ddd</label>', Html::renderCheckableInputList('xxx', 'checkbox', array('aaa' => 'bbb', 'ccc' => 'ddd'), $f, array('lang' => 'fr')));

        $f->setData(array('xxx' => 'ccc'));
        $this->assertEquals('<label lang="fr"><input class="y" name="xxx" value="aaa" type="radio" />bbb</label><label lang="fr"><input class="y" name="xxx" value="ccc" type="radio" checked="checked" />ddd</label>', Html::renderCheckableInputList('xxx', 'radio', array('aaa' => 'bbb', 'ccc' => 'ddd'), $f, array('lang' => 'fr'), array('class' => 'y')));
        $f->setData(array('xxx' => array('ccc')));
        $this->assertEquals('<label lang="fr"><input class="y" name="xxx[]" value="aaa" type="checkbox" />bbb</label><label lang="fr"><input class="y" name="xxx[]" value="ccc" type="checkbox" checked="checked" />ddd</label>', Html::renderCheckableInputList('xxx', 'checkbox', array('aaa' => 'bbb', 'ccc' => 'ddd'), $f, array('lang' => 'fr'), array('class' => 'y')));
    }

    public function test_buildDatatable() {
        //TODO à implémenter
    }

}
