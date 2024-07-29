<?php

namespace Alexplusde\Ymca;

class Pdf
{
    public static function toCamelCase($input)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $input)));
    }
    public static function toClassName($input)
    {
        return str_replace('rex_', '', $input);
    }
    public static function getTypeTemplate($type_name)
    {
        $typeTemplates =
        [
        'value' => '<p class="%3$s"><strong>%4$s: </strong><?= $dataset->get%2$s() ?></p>',
        'checkbox' => '<p> class="%3$s"><strong>%4$s: </strong><?php if ($dataset->get%2$s(true)) { ?>ja<?php } else { ?>nein<?php }; ?>',
        'textarea' => '<div class="%3$s"><?= $dataset->get%2$s() ?></div>',
        'datetime' => '<p class="%3$s"><strong>%4$s: </strong><?= $dataset->getFormatted%2$s() ?></p>',
        'be_media' => '<p class="%3$s"><strong>%4$s: </strong><img src="<?= $dataset->get%2$s(true)->getUrl() ?>" alt="" /></p>',
        'be_table' => '<div class="%3$s"><?php $collection = $dataset->getRelatedCollection("get%2$s()") 
        
        foreach($collection as $item) {
            // echo $item->getName();
        }
        
        ?></div>',
        'datestamp' => '<p class="%3$s"><strong>%4$s: </strong><?= $dataset->getFormatted%2$s() ?></p>',
        'int' => '<p class="%3$s"><strong>%4$s: </strong><?= $dataset->get%2$s() ?></p>',
        'number' => '<p class="%3$s"><strong>%4$s: </strong><?= $dataset->get%2$s() ?></p>',
        'prio' => '<!-- prio (wird nicht im Template ausgegeben) -->',
        'time' => '<p class="%3$s"><strong>%4$s: </strong><?= $dataset->getFormatted%2$s() ?></p>',
        'domain' => '<div class="%3$s"><strong>%4$s: </strong><?= $dataset->get%2$s() ?></div>',
        'be_user' => '<div class=""><strong>%4$s: </strong><?= $dataset->get%2$s()->getLogin() ?></div>',
        'be_link' => '<p class="%3$s"><strong>%4$s: </strong><?= $dataset->get%2$s() ?></p>',
        'relation' => '<?php $relation = $dataset->getRelatedDataset("get%2$s()") ?><div class="%3$s"><strong>%4$s: </strong><?php # echo $relation->getName() ?></div>',
        'collection' => '<div class="%3$s"><?php $collection = $dataset->getRelatedCollection("get%2$s()");
                foreach($collection as $item) {
                        # echo $item->getName();
                }        
        ?>
        </div>',
        'choice' => '<p class="%3$s"><strong>%4$s: </strong><?= $dataset->get%2$s() ?></p>',
        ];

        return $typeTemplates[$type_name] ?? '';
    }


}
