<?php

class com_bul7_wp_FacebookLikeButtonForDummiesAdmin {
    /**
     * @var com_bul7_wp_FacebookLikeButtonForDummies
     */
    private $plugin;

    public function __construct($plugin) {
        $this->plugin = $plugin;
        add_options_page('Facebook Like Button for Dummies', 'FB Like 4 Dummies', 'administrator', get_class($this), array(&$this, 'goAdminSettingsPage'));
        add_filter( 'plugin_action_links', array($this, 'filterPluginActionLinks'), 10, 2 );

    }

    public function filterPluginActionLinks($links, $file) {
        if ($file == plugin_basename( dirname(__FILE__).'/plugin.php')) {
            $links[] = '<a href="options-general.php?page='.get_class($this).'">Settings</a>';
        }
        return $links;
    }
    public function goAdminSettingsPage() {
        if (!class_exists('b7_HtmlHelper')) require_once(dirname(__FILE__).'/b7_HtmlHelper.php');
        $isSubmitted = isset($_POST['formToken']) && $_POST['formToken'] == $this->getFormToken();

        $messages = array();
        if ($isSubmitted && isset($_POST['do_update_settings'])) {
            $this->plugin->hideAllLikeButtons = (isset($_POST['hideAllLikeButtons']) && $_POST['hideAllLikeButtons'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->beforeSingleShow = (isset($_POST['beforeSingleShow']) && $_POST['beforeSingleShow'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->afterSingleShow = (isset($_POST['afterSingleShow']) && $_POST['afterSingleShow'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->beforePageShow = (isset($_POST['beforePageShow']) && $_POST['beforePageShow'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->afterPageShow = (isset($_POST['afterPageShow']) && $_POST['afterPageShow'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->likeButtonShowSend = (isset($_POST['likeButtonShowSend']) && $_POST['likeButtonShowSend'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->likeButtonLayoutStyle = (isset($_POST['likeButtonLayoutStyle']) ? $_POST['likeButtonLayoutStyle'] : '');
            if (isset($_POST['likeButtonWidth']) && is_numeric($_POST['likeButtonWidth']) && intval($_POST['likeButtonWidth']) > 0) {
                $this->plugin->likeButtonWidth = intval($_POST['likeButtonWidth']);
            } else {
                $messages[] = 'Error: Like Button Width must be integer, greater than zero. Value not updated.';
            }
            $this->plugin->likeButtonShowFaces = (isset($_POST['likeButtonShowFaces']) && $_POST['likeButtonShowFaces'] == b7_HtmlHelper::CHECKBOX_VALUE);
            $this->plugin->likeButtonVerb = (isset($_POST['likeButtonVerb']) ? $_POST['likeButtonVerb'] : '');
            $this->plugin->likeButtonFont = (isset($_POST['likeButtonFont']) ? $_POST['likeButtonFont'] : '');
            $this->plugin->likeButtonScheme = (isset($_POST['likeButtonScheme']) ? $_POST['likeButtonScheme'] : '');
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

            <h3>Like Button Appearance</h3>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">&nbsp;</th>
                    <td><?php echo b7_HtmlHelper::checkbox('likeButtonShowSend', $likeButtonShowSend, array(
                            'id' => 'likeButtonShowSend'
                        )); ?>
                        <label for="likeButtonShowSend">Show Send Button</label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="likeButtonLayoutStyle">Layout Style</label></th>
                    <td><?php echo b7_HtmlHelper::listbox('likeButtonLayoutStyle', explode(',', ',standard,button_count,box_count'), $likeButtonLayoutStyle, array(
                            'id' => 'likeButtonLayoutStyle'
                        )); ?>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="likeButtonWidth">Width</label></th>
                    <td><?php echo b7_HtmlHelper::textBox('likeButtonWidth', $likeButtonWidth, array(
                            'id' => 'likeButtonWidth',
                            'size' => 12
                        )); ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">&nbsp;</th>
                    <td><?php echo b7_HtmlHelper::checkbox('likeButtonShowFaces', $likeButtonShowFaces, array(
                            'id' => 'likeButtonShowFaces'
                        )); ?>
                        <label for="likeButtonShowFaces">Show Faces with Like Button</label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="likeButtonVerb">Verb to Display</label></th>
                    <td><?php echo b7_HtmlHelper::listbox('likeButtonVerb', explode(',', ',like,recommend'), $likeButtonVerb, array(
                            'id' => 'likeButtonVerb'
                        )); ?>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="likeButtonFont">Font</label></th>
                    <td><?php echo b7_HtmlHelper::listbox('likeButtonFont', explode(',', ',arial,lucida grande,segoe ui,tahoma,trebuchet ms,verdana'), $likeButtonFont, array(
                            'id' => 'likeButtonFont'
                        )); ?>
                        
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="likeButtonScheme">Color Scheme</label></th>
                    <td><?php echo b7_HtmlHelper::listbox('likeButtonScheme', explode(',', ',light,dark'), $likeButtonScheme, array(
                            'id' => 'likeButtonScheme'
                        )); ?>

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

            <h3>Other</h3>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">&nbsp;</th>
                    <td><?php echo b7_HtmlHelper::checkbox('hidePoweredBy', $hidePoweredBy, array(
                            'id' => 'hidePoweredBy'
                        )); ?>
                        <label for="hidePoweredBy">Hide Powered By Link</label>
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
