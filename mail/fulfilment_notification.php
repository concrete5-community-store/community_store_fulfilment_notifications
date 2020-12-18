<?php
defined('C5_EXECUTE') or die("Access Denied.");

$locale = $order->getLocale();
if ($locale) {
    \Concrete\Core\Localization\Localization::changeLocale($locale);
}

$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
$dh = $app->make('helper/date');
$csm = $app->make('cs/helper/multilingual');
$subject = t("Order #%s Update", $order->getOrderID());

/*
 * HTML BODY START
 */
ob_start();

?>
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
</head>
<body>
<?= $csm->t(trim(\Concrete\Core\Support\Facade\Config::get('community_store.receiptHeader')), 'receiptEmailHeader'); ?>

<?php
$trackingURL = $order->getTrackingURL();
$carrier = $order->getCarrier();
$trackingCode = $order->getTrackingCode();
$orderID = $order->getOrderID();
$orderStatus = $order->getStatus();
?>

<h3><?= t('Order #%s has been updated', $orderID ); ?></h3>

<p><?= t('Dear %s,', $order->getAttribute('billing_first_name')); ?></p>
<p><?= t('The shipping status of your order #%s is now:', $orderID); ?> <strong><?= h($orderStatus); ?></strong></p>

<?php if ($trackingURL) { ?>
    <p><strong><?= t('View shipment tracking');?>:</strong> <a href="<?= $trackingURL; ?>"><?= h($trackingURL);?></a></p>
<?php } ?>

<p>
<?php if ($carrier) { ?>
<strong><?= t('Carrier');?>:</strong> <?= h($carrier);?><br />
<?php } ?>

<?php if ($trackingCode) { ?>
    <strong><?= t('Tracking Code');?>:</strong> <?= h($trackingCode);?>
<?php } ?>
</p>

<?= $csm->t(trim(\Concrete\Core\Support\Facade\Config::get('community_store.receiptFooter')), 'receiptEmailFooter'); ?>

</body>
</html>

<?php
$bodyHTML = ob_get_clean();
/*
 * HTML BODY END
 *
 */
?>
