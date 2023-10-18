<?php
if (!isset($classNamespace, $classFullname, $className)) {
    echo "Undeclared template variables.\n";

    exit(1);
}
?>

declare(strict_types=1);

<?php if ($classNamespace) { ?>
namespace SlackPhp\BlockKit\Tests\<?php echo $classNamespace; ?>;
<?php } else { ?>
namespace SlackPhp\BlockKit\Tests;
<?php } ?>

use SlackPhp\BlockKit\<?php echo $classFullname; ?>;
<?php if ($classNamespace) { ?>
use SlackPhp\BlockKit\Tests\TestCase;
<?php } ?>

/**
 * @covers \SlackPhp\BlockKit\<?php echo $classFullname; ?>

 */
class <?php echo $className; ?>Test extends TestCase
{
    public function testThatSomethingDoesSomething()
    {
        $this->assertTrue(class_exists(<?php echo $className; ?>::class));
    }
}
