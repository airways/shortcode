{ce:core:include template="global/_header"}


    
    <h1>Shortcode Module</h1>
    <!-- <kbd>PUBLISH</kbd> -->
    
    <p><dfn><a href="http://devot-ee.com/add-ons/shortcode">Shortcode</a></dfn> is a module designed to give you the ability to inject dynamic content inside of
    any custom field you have defined.</p>
    
    <p>At it's most basic usage, Shortcode allows the creation and re-use of <b>macros</b> of content that can be defined by an individual author or globally for the entire site.</p>
    
    <p>Additionally, the shortcodes feature will enable you to insert forms from <a href="http://devot-ee.com/add-ons/proform-drag-and-drop-form-builder/">ProForm</a>, embed tweets from Twitter, and a number of other things that would be far more complex or impossible to implement inside editor-managed content without the <dfn>Shortcode</dfn> module.</p>
    
    <h2>Features</h2>
    <p><dfn>ShortCode</dfn> has the following features:</p>
    <ul>
        <li>User defined macros that can be inserted into any content authored by each user</li>
        <li>System-side macros that are shared amongst all users and content on the site</li>
        <li>Add-on provided shortcodes that inject dynamic functionality inside of content - complete with a dialog box to build parameters for each shortcode</li>
    </ul>

    <h3>Compatible Add-ons</h3>
    <p>The following add-ons are known to provide shortcodes that can be used with <dfn>Shortcode</dfn>. More are added all the time! If you'd like to implement support for Shortcode for an add-on, simply read the <a href="#creating_shortcodes">Creating Shortcodes</a> section below. Any add-on can provide shortcodes that are actually implemented by other add-ons, so you don't even need to be the add-on author to create and release a shortcode for it!</p>
    <ul>
    <li>ProForm - inject fully functional forms into the middle of content</li>
    <li>Will Hunting - provide support for evaluating math expressions inside of content</li>
    </ul>
    
    <h2>ShortCode Documentation</h2>

    <h3>Functionality</h3>
    
    <ul>
        <li><a href="#macros">Managing Macros</a></li>
        <li><a href="#using_macros">Using Macros</a></li>
        <li><a href="#using_shortcodes">Using Shortcodes</a></li>
        <li><a href="#parse_tag">Parsing in Templates</a></li>
        <li><a href="#shortcode_docs">Shortcode Documentation</a></li>
        <li><a href="#creating_shortcodes">Creating Shortcodes</a></li>
    </ul>

    <h2><a name="global">Managing Macros</a></h2>
    <p>Macros are managed through the main <dfn>Shortcode</dfn> interface, accessible from the EE menu at <kbd>Add-ons > Modules > Shortcode</kbd>.</p>
    
    <p>There are two "scopes" for any macros you might create:</p>
    
    <ul>
    <li>My Macros - accessible only in content authored by the active user</li>
    <li>Site Macros - accessible in any content on the site</li>
    </ul><br/>
    
    <p>Only users with Super Admin access can create Site macros.</li>
    
    <h3>Creating a Macro</h3>
    <p>To create a Macro:</p>
    <ol>
    <li>Visit the Shortcode page at <kbd>Add-ons > Modules > Shortcode</kbd></li>
    <li>Click either My Macros or Site Macros (optional, the default view is of the My Macros list)</li>
    <li>Click the Create Macro button</li>
    </ol>
    
    <p>You will be asked to provide the following values for the new macro:</p>
    
    <table>
        <tr><th width="200">Setting</th><th>Description</th></tr>
        <tr><td width="200"><b>Type</b></td><td>Select <b>Text</b> for a single line of text, appropriate for short values. Select <b>Textarea</b> for longer pieces of text.</td></tr>
        <tr><td width="200"><b>Name</b></td><td>The name of the macro. This should be a short but descriptive name that will be easy to remember. This name, combined with the scope of the macro, is used to insert it into content.</td></tr>
    </table>

    <h2><a name="using_macros">Using Macros</a></h2>
    
    <p>Using macros in content is extremely simple.</p>
    
    <p>Macros can be used in any field in ExpressionEngine that allows you to enter plain text in some form. Uou may reference your macros by prefixing the name of the macro with the scope it is defined in - separated by a colon - and wrapping the entire thing in square brackets. When you do so, the value set for the macro will be replaced into the published output.</p>
    
    <p>For instance, say you create a macro in the "mine" scope with the name "address". You would use this macro in any content you are set as the Author of by using a token like so:</p>
    
    <div class="tip">
        <h6>Example Usage</h6>
        <p>Accessing a macro named "address" in the "mine" scope:</p>
        <pre class="brush: xml">
            &lt;p&gt;My office location is [mine:address].&lt;/p&gt;
        </pre>
    </div>
    
    <p>Similarly, if you are a Super Admin and create a macro in the scope "site" with the name "slogan", any content authors could insert the value of this macro into content published in the entire site with this token:</p>
    
    <div class="tip">
        <h6>Example Usage</h6>
        <p>Accessing a macro named "slogan" in the "site" scope:</p>
        <pre class="brush: xml">
            &lt;p&gt;Remember, [site:slogan]&lt;/p&gt;
        </pre>
    </div>

    <p>That's really all there is to using macros!</p>


    <h2><a name="using_shortcodes">Using Shortcodes</a></h2>
    
    <p>Using shortcodes in your content is similar to using macros.</p>
    
    <p>The same basic syntax is used - a tag name inside of square brackets - but the prefix for shortcodes is designated by the plugin or module that provides the shortcode, and may be absent completely.</p>
    
    <p>For example, <a href="http://devot-ee.com/add-ons/proform-drag-and-drop-form-builder/">ProForm</a> provides a shortcode which can be used to inject a form into a piece of content like so:</p>
    
    <div class="tip">
        <h6>Example Usage</h6>
        <p>Injecting a form into content using ProForm's [form] shortcode:</p>
        <pre class="brush: xml">
            &lt;p&gt;Please fill out this form to apply for the posting:&lt;/p&gt;
            [form form_name="application_form"]
        </pre>
    </div>
    
    <p>As you can see, shortcodes can accept parameters, which are passed on to the plugin or module that provides the shortcode, and which it can use to customize the output for the shortcode.</p>
    
    <p>See the <a href="#shortcode_docs">Shortcode Documentation</a> section for a list of built-in shortcodes provided out of the box.</p>
     
    <h3><a name="insert_shortcode">Insert Shortcode Dialog</a></h2>

    <p>If you make use of the Rich Text Editor provided with ExpressionEngine, a new Insert Macro / Shortcode icon will be added to the toolbar. When you click this button, a list of all possible macros and shortcodes will be presented. Upon selecting one of these options, such as the [form] shortcode, the required parameters for the Shortcode will be presented for you to fill out.</p>

    <h2><a name="parse_tag">Parsing in Templates</a></h2>
    
    <p>Shortcode's parsing will automatically be triggered on the output of any <kbd>Channel Entries</kbd> tag. To use macros and shortcodes in other places in your templates, you need to wrap them in the <kbd>Parse Tag</kbd> provided by Shortcode.</p>
    
    <p><b>Remember:</b> For security purposes, you should never call the <kbd>Parse Tag</kbd> on content that can be entered by visitors to your site, such as comment forms or segment variables.</p>
    
    <div class="tip">
        <h6>Example Usage</h6>
        <pre class="brush: xml">
            {exp:shortcode:parse parse="inward"}
                ...
            {/exp:shortcode:parse}
        </pre>
    </div>
    

    <h2><a name="shortcode_docs">Shortcode Documentation</a></h2>

    <p>For documentation on the shortcodes available built in to the module as well as by other add-ons you may have installed, goto <kbd>Add-ons > Modules > Shortcode</kbd> inside ExpressionEngine then click the <kbd>Add-on Shortcodes</kbd> button in the top right. This will display a list of all available shortcodes and their documentation.</p>
    
    <p>Currently Shortcode has the following codes available by default:</p>
    
    <ul>
        <li>[tweet] - embed a tweet from Twitter, built-in to Shortcode</li>
        <li>[form] - embed a form, requires <a href="http://devot-ee.com/add-ons/proform-drag-and-drop-form-builder/">ProForm</a></li>
        <li>[math] - evaluate a math expression, requires <a href="http://devot-ee.com/add-ons/will-hunting/">Will Hunting</a></li>
    </ul>

    <h2><a name="creating_shortcodes">Creating Shortcodes</a></h2>

    <p>Shortcodes can be provided by plugins or modules.</p>
    
    <p>A shortcode consists of the definition of it's parameters, documentation, and other meta data - coupled with the template tag that actually implements the shortcode. These template tags may be, and often are, pre-existing tags that can also be used directly in normal ExpressionEngine templates. The meta data tells Shortcode how to wrap up these template tags into a usable form within content - using the shortcode syntax.</p>
    
    <p>Shortcode meta data is specified in a global function named init_shortcodes(), which should be defined on the main plugin or module file.  Note that it's perfectly fine for an add-on to register shortcodes on another add-ons behalf to add support if the original add-on doesn't support Shortcode.</p>
    
    <div class="tip">
        <h6>Example Usage</h6>
        <p>Creating a new shortcode mapped to a plugin named <b>twooter</b> with a tag named <b>twoot</b>, which accepts a single parameter named <b>id</b>. This method must appear in a plugin or module to be used.</p>
        <pre class="brush: xml">
            public function init_shortcodes()
            &#123;
                return array(
                    'twoot' => = array(
                        'class' => 'twooter',
                        'method' => 'render_tweet',
                        'label' => '[twoot] - Embed a Tweet differently from the built-in Twitter support',
                        'params' => array(
                            array('type' => 'input', 'name' => 'id', 'label' => 'ID of Tweet'),
                        ),
                        'docs' => "<p>Documentation for this shortcode</p> [twoot id='12345']"
                    )
                );
            &#125;
        </pre>
    </div>
    
    <p>The <b>array keys</b> ("twoot" in the example) in the array are the actual names of the shortcodes that must be used to reference them in content. You may define multiple shortcodes by including more than one key / array pair in the returned array.</p>
    
    <p>The <b>class</b> string ("twooter" in the example) is set to the name of the plugin or module that should be triggered when the shortcode is rendered. This does not have to be set to the same class name as the add-on that is actually registering the shortcode. If this value is not included it will default to the current add-on's class name.</p>
    
    <p>The <b>method</b> string ("render_tweet" in the example) is set to the actual tag to trigger and pass the shortcode's parameters to. This method must exist and be plugin on the <b>class</b> set.</p>
    
    <p>The <b>label</b> string is set to a value used to display the shortcode in the Insert Macro / Shortcode dialog box, and in the <kbd>Add-on Shortcodes</kbd> listing. This value should <b>always</b> start with the short name of the shortcode in square brackets, as shown.</p>
    
    <p>The <b>docs</b> string should be at least somewhat more extensive than the above example, providing a list of parameters and their allowed values. Multiple real-world examples are a good idea as well.</p>
    
    <p>The <b>params</b> element for each shortcode may be an empty array if the shortcode does not need any parameters (which is probably rare), or may consist of multiple arrays defining the parameters needed by the shortcode.</p>
    
    <p>You may use the following types for each parameter:</p>
    
    <table>
        <tr><th>Type</th><th>Description</th></tr>
        <tr><td>input</td><td>A simple text input field.</td></tr>
        <tr><td>dropdown</td><td>A dropdown select list which is populated from the attribute <b>options</b> added to the field's array. The options array should consist of keys that will be set as the actual parameter, and values which are used as the labels for each option.</td></tr>
    </table>
    
   
{ce:core:include template="global/_footer"}
