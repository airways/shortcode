{ce:core:include template="global/_header"}


    
    <h1>Shortcode Module</h1>
    <!-- <kbd>PUBLISH</kbd> -->
    
    <p><dfn>Shortcode</dfn> is a module designed to give you the ability to inject dynamic content inside of
    any custom field you have defined.</p>
    
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
        <li><a href="#shortcodes">Using Shortcodes</a></li>
        <li><a href="#shortcode_docs">Shortcode Documentation</a>
            <ul>
                <li><a href="#shortcode_form">[form]</a></li>
                <li><a href="#shortcode_math">[math]</a></li>
            </ul>
        </li>
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
    
    <p>See the <a href="#shortcode_docs">Shortcode Documentation</a> section for documentation on built-in shortcodes provided out of the box.</p>
     
    <!--
    <h3><a name="insert_shortcode">Insert Shortcode Dialog</a></h2>

    <p>If you make use of the Rich Text Editor provided with ExpressionEngine, a new Insert Macro / Shortcode icon will be added to the toolbar. When you click this button, a list of all possible macros and shortcodes will be presented. Upon selecting one of these options, such as the [form] shortcode, the required parameters for the Shortcode will be presented for you to fill out.</p>
    -->

    <h2><a name="shortcode_docs">Shortcode Documentation</a></h2>

    <h3><a name="shortcode_form">[form]</a></h3>
    
    <p>The [form] shortcode is available if you have installed <a href="http://devot-ee.com/add-ons/proform-drag-and-drop-form-builder/">ProForm</a>. This shortcode allows you to insert a form into a page wherever you need it.</p>
    
    <h4>Parameters</h4>
    
    <p>The [form] shortcode accepts one paremters, the name of the form to render:</p>
    
    <ul>
        <li><b>form_name</b> - name of the form to render</li>
    </ul>

    <h3><a name="shortcode_math">[math]</a></h3>
    
    <p>The [math] shortcode is available if you have installed <a href="http://devot-ee.com/add-ons/will-hunting/">Will Hunting</a>. This shortcode allows you to evaluate math expressions in your content.</p>
    
    <h4>Parameters</h4>
    
    <p>The [math] shortcode accepts one paremters, the actual expression to evaluate:</p>
    
    <ul>
        <li><b>math_exp</b> - the expression to evaluate</li>
    </ul>


    
{ce:core:include template="global/_footer"}
