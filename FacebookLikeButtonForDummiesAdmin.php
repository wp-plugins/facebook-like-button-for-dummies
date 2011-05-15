<?php

class com_bul7_wp_FacebookLikeButtonForDummiesAdmin {
    /**
     * @var com_bul7_wp_FacebookLikeButtonForDummies
     */
    private $plugin;

    public function __construct($plugin) {
        $this->plugin = $plugin;
        add_options_page('Facebook Like Button for Dummies', 'FB Like 4 Dummies', 'administrator', get_class($this), array(&$this, 'adminSettingsPage'));
    }

    public function adminSettingsPage() {
        if (!class_exists('b7_HtmlHelper')) require_once(dirname(__FILE__).'/b7_HtmlHelper.php');
        $isSubmitted = isset($_POST['formToken']) && $_POST['formToken'] == $this->getFormToken();

        $messages = array();
        if ($isSubmitted && isset($_POST['do_update_settings'])) {
            $this->plugin->hideAllLikeButtons = (isset($_POST['hideAllLikeButtons']) && $_POST['hideAllLikeButtons'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->beforeSingleShow = (isset($_POST['beforeSingleShow']) && $_POST['beforeSingleShow'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->afterSingleShow = (isset($_POST['afterSingleShow']) && $_POST['afterSingleShow'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->beforePageShow = (isset($_POST['beforePageShow']) && $_POST['beforePageShow'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->afterPageShow = (isset($_POST['afterPageShow']) && $_POST['afterPageShow'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->openGraphEnable = (isset($_POST['openGraphEnable']) && $_POST['openGraphEnable'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->openGraphImage = (isset($_POST['openGraphImage']) ? $_POST['openGraphImage'] : '');
            $this->plugin->fbAdmins = (isset($_POST['fbAdmins']) ? $_POST['fbAdmins'] : '');
            if (isset($_POST['openGraphDescLen']) && is_numeric($_POST['openGraphDescLen']) && intval($_POST['openGraphDescLen']) > 0) {
                $this->plugin->openGraphDescLen = intval($_POST['openGraphDescLen']);
            } else {
                $messages[] = 'Error: Max Description Length for Open Graph Protocol must be greater than zero integer. Value not updated.';
            }
            $this->plugin->saveSettings();
            $messages[] = 'Settings saved';

        }

        $this->printForm($this->plugin->options, $messages);
    }

    private function printForm($vars, $messages) {
        extract($vars);
?>
    <div class="wrap">
    <h2>Facebook Like Button For Dummies</h2>
        <?php if ($messages) { ?>
            <div>
                <ul>
                    <?php foreach ($messages as $m) { echo "<li>$m</li>"; } ?>
                </ul>
            </div>
        <?php } // messages ?>
        <form method="POST">
            <input type="hidden" name="formToken" value="<?php echo $this->getFormToken(); ?>" />
            <h3>Facebook Like Button Settings</h3>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">&nbsp;</th>
                    <td><?php echo b7_HtmlHelper::checkbox('hideAllLikeButtons', $hideAllLikeButtons, array(
                            'id' => 'hideAllLikeButtons'
                        )); ?>
                        <label for="hideAllLikeButtons">Hide all like buttons</label>
                    </td>
                </tr>         
                <tr valign="top">
                    <th scope="row">Like Button for Posts</th>
                    <td><?php echo b7_HtmlHelper::checkbox('beforeSingleShow', $beforeSingleShow, array(
                            'id' => 'beforeSingleShow'
                        )); ?>
                        <label for="beforeSingleShow">Place Like Button Before Post Content</label> <br />
                        <?php echo b7_HtmlHelper::checkbox('afterSingleShow', $afterSingleShow, array(
                            'id' => 'afterSingleShow'
                        )); ?>
                        <label for="afterSingleShow">Place Like Button After Post Content</label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Like Button for Pages</th>
                    <td><?php echo b7_HtmlHelper::checkbox('beforePageShow', $beforePageShow, array(
                            'id' => 'beforePageShow'
                        )); ?>
                        <label for="beforePageShow">Place Like Button Before Page Content</label> <br />
                        <?php echo b7_HtmlHelper::checkbox('afterPageShow', $afterPageShow, array(
                            'id' => 'afterPageShow'
                        )); ?>
                        <label for="afterPageShow">Place Like Button After Page Content</label>
                    </td>
                </tr>
            </table>

            <h3>Facebook OpenGraph Protocol</h3>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">&nbsp;</th>
                    <td><?php echo b7_HtmlHelper::checkbox('openGraphEnable', $openGraphEnable, array(
                            'id' => 'openGraphEnable'
                        )); ?>
                        <label for="openGraphEnable">Enable OpenGraph Protocol</label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="openGraphImage">Default Image</label></th>
                    <td><?php echo b7_HtmlHelper::textBox('openGraphImage', $openGraphImage, array(
                            'id' => 'openGraphImage',
                            'size' => 64
                        )); ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="openGraphDescLen">Max Description Length</label></th>
                    <td><?php echo b7_HtmlHelper::textBox('openGraphDescLen', $openGraphDescLen, array(
                            'id' => 'openGraphDescLen',
                            'size' => 12
                        )); ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="fbAdmins">Facebook Admins</label></th>
                    <td><?php echo b7_HtmlHelper::textBox('fbAdmins', $fbAdmins, array(
                            'id' => 'fbAdmins',
                            'size' => 64
                        )); ?>
                    </td>
                </tr>
            </table>

            <p class="submit"><input type="submit" name="do_update_settings" value="Update Settings" /></p>
        </form>
    </div>
<?php
    }

    private function getFormToken() {
        return get_class($this).'_settings';
    }
}

?>
