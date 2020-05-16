<?php

$lang['friendlyname'] = 'Extended Content Blocks 2';
$lang['postinstall'] = 'Extended Content Blocks 2 was successful installed';
$lang['postuninstall'] = 'Extended Content Blocks 2 was successful uninstalled';
$lang['really_uninstall'] = 'Really? Are you sure you want to unsinstall this fine module?';
$lang['uninstalled'] = 'Module Uninstalled.';
$lang['installed'] = 'Module version %s installed.';
$lang['upgraded'] = 'Module upgraded to version %s.';
$lang['admindescription'] = 'This module adds new content  blocks to CMS Made Simple v2+';


$lang['selected'] = 'Selected';
$lang['select'] = 'Select';
$lang['refresh'] = 'Refresh';

$lang['content_block_label_selected'] = 'Selected';
$lang['content_block_label_available'] = 'Available';
$lang['drop_items'] = 'No items selected - drop selected items here';
$lang['drop_required_items'] = 'Drop %s required items here';
$lang['remove'] = 'Remove';

$lang['udt_error'] = 'UDT \'%s\' does not exist';



//**************************************************************************************************
$lang['help'] = '


<h3>What Does This Do?</h3>
<p>Module adds aditional content blocks for CMS Made Simple v2+. This is a fork of the original ECB module that worked with CMSMS v1.</p>
<p>Provides the following field types:<br>
<ol>
   <li>file_selector</li>
   <li>color_picker</li>
   <li>dropdown_from_udt</li>
   <li>dropdown</li>
   <li>checkbox</li>
   <li>module_link</li>
   <li>link</li>
   <li>timepicker</li>
   <li>datepicker</li>
   <li>input</li>
   <li>textarea</li>
   <li>editor (textarea with wysiwyg)</li>
   <li>text </li>
   <li>pages </li>
   <li>hr (horizontal line)</li>
   <li>sortablelist</li>
   <li>radio</li>
   <li>hidden <span style="color:red;">newish</span></li>
   <li>fieldset_start <span style="color:red;">new</span></li>
   <li>fieldset_end <span style="color:red;">new</span></li>
</ol>
</p><br>

<p>If you like this module please <a href="http://www.cmsmadesimple.org/about-link/donations/" target="_blank" style="font-weight:bold;">donate to CMSMS</a></p><br>

<h3>Fields</h3>

<p><strong>file_selector</strong></p>
<p>Example:  {content_module module="ECB2" field="file_selector" block="test10" dir="images" filetypes="jpg,gif,png" excludeprefix="thumb_"}        </p>
<p>Parameters:
filetypes - comma separated<br>
dir (optional) - default uploads/<br>
excludeprefix (optional)<br>
recurse (optional)<br>
sortfiles (optional)<br>
preview (optional) - only for images<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>color_picker</strong></p>
<p>Example:  {content_module module="ECB2" field="color_picker" block="test1" label="Color" default_value="#000000"}</p>
<p>Parameters:
default_value (optional)<br>
size (optional) - default 10<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>dropdown_from_udt</strong></p>
<p>Example: {content_module module="ECB2" field="dropdown_from_udt" block="test2" label="Gallery" udt="mycustomudt"  first_value="=select="}</p>
<p>Ouput from UDT must be array() - example: return array("label"=>"value", "label 2 "=>"value 2")</p>
<p>Parameters:
udt (required) - udt name<br>
first_value (optional) <br>
multiple (optional) - add multiple option select support<br>
size (optional) - multiple enabled only<br>
description (optional) - adds additional text explanation for editor
</p>
<p><strong>Examples UDT</strong>:
<br>
<a href="https://gist.github.com/kuzmany/6779c193b8104aa6abfe">Gallery list from Gallery module</a> <br>
<a href="https://gist.github.com/kuzmany/464276e16f3b74c07555">Group list from FEU</a> <br>
<a href="https://gist.github.com/kuzmany/51583c6439cb041679a6">Users list from FEU</a>
</p><br>

<p><strong>dropdown</strong></p>
<p>Example: {content_module module="ECB2" field="dropdown" block="test5" label="Fruit"  values="Apple=apple,Orange=orange" first_value="select fruit"}</p>
<p>Parameters:
values (required) - comma separated. Example: Apple=apple,Orange=orange,Green=green <br>
first_value (optional)<br>
multiple (optional) - add multiple option select support<br>
size (optional) - multiple enabled only<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>checkbox</strong></p>
<p>Example: {content_module module="ECB2" field="checkbox" block="test11" label="Checkbox" default_value="1"}</p>
<p>Parameters:
default_value (optional)<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>module_link</strong></p>
<p>Example: {content_module module="ECB2" field="module_link" label="Module edit" block="test3" mod="Cataloger" text="Edit catalog" }</p>
<p>Parameters:
mod (required) <br>
text (required) <br>
target (optional) - default _self<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>link</strong></p>
<p>Example: {content_module module="ECB2" field="link" label="Search" block="test4" target="_blank" link="http://www.bing.com" text="bing search"}</p>
<p>Parameters:
link (required) <br>
text (required) <br>
target (optional) - default _self<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>timepicker</strong></p>
<p>Example: {content_module module="ECB2" field="timepicker" label="Time" block="test45"}</p>
<p>Parameters:
size (optional) default 100<br>
time_format (optional) default HH::ss<br>
max_length (optional) default 10<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>datepicker</strong></p>
<p>Example: {content_module module="ECB2" field="datepicker" label="Date" block="test44"}</p>
<p>Parameters:
size (optional) default 100<br>
date_format (optional) default yy-mm-dd<br>
time (optional) - add time picker default 0<br>
time_format (optional) default HH::ss<br>
max_length (optional) default 10 <br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>input</strong></p>
<p>Example: {content_module module="ECB2" field="input" label="Text" block="test5" size=55 max_length=55 default_value="fill it"}</p>
<p>Parameters:
size (optional) default 30<br>
max_length (optional) default 255 <br>
default_value (optional) - default value for input<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>textarea</strong></p>
<p>Example: {content_module module="ECB2" field="textarea" label="Textarea" block="test6" rows=10 cols=40 default_value="fill it"}</p>
<p>Parameters:
rows (optional) default 20<br>
cols (optional) default 80 <br>
default_value (optional) - default value for textarea<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>editor (textarea with wysiwyg)</strong></p>
<p>Example: {content_module module="ECB2" field="editor" label="Textarea" block="test7" rows=10 cols=40 default_value="fill it"}</p>
<p>Parameters:
rows (optional) default 20<br>
cols (optional) default 80 <br>
default_value (optional) - default value for textarea<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>text </strong></p>
<p>Example: {content_module module="ECB2" field="text" label="Text" block="test8" text="Hello word!"}</p>
<p>Parameters:
text (required) text in admin (add information for users)<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>pages </strong></p>
<p>Example: {content_module module="ECB2" field="pages" label="Page" block="test10"}</p>
<p>Parameters:
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>hr (horizontal line)</strong></p>
<p>Example: {content_module module="ECB2" field="hr" label="Other blocks" block="blockname"}<p>
Parameters:
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>sortablelist</strong></p>
<p>Example: {content_module module="ECB2" field="sortablelist" block="testsortablelist" label="Choose fruit" udt="mydut"}</p>
<p>Parameters:
values (optional) - comma separated. Example: \'Apple=apple,Orange=orange,Green=green,value=Label\' <br>
udt (optional) - name of udt that returns an array in the format \'value\' => \'Label\',<br>
first_value (optional)<br>
label_left (optional)<br>
label_right (optional)<br>
max_number (optional) - limits the maximum number of items that can be selected<br>
required_number (optional) - sets a specific number of items that must be selected (or none)<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>radio</strong> </p>
<p>Example: {content_module module="ECB2" field="radio" block="test17" label="Fruit" values="Apple=apple,Orange=orange,Kiwifruit=kiwifruit" default_value="Orange"}</p>
<p>Parameters:
values (required) - comma separated. Example: Apple=apple,Orange=orange,Kiwifruit=kiwifruit<br>
default_value (optional) - default is first choice - set to default value e.g. "Orange"
inline (optional) - if set displays admin radio buttons inline<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>hidden</strong> <span style="color:red;">new</span></p>
<p>Example: {content_module module=\'ECB2\' block=\'test18hidden\' assign=\'testhidden\' field=\'hidden\' value=\'markervalue\'}<br>
Can be used to set a page attribute that can then be accessed (e.g. from a Navigator-Template), using {page_attr page=$node->alias key=\'testhidden\'}</p>
<p>Parameters:
value (required) - hidden value to be saved<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>fieldset_start</strong> <span style="color:red;">new</span></p>
<p>Example: {content_module module=\'ECB2\' field=\'fieldset_start\' label=\'& nbsp;\' block=\'test19fieldset\' assign=\'test19fieldset\' legend=\'Fieldset Test Legend\' description=\'Can add a description in here\'}<br>
Creates the start of a fieldset for grouping relavant admin fields together. Note: a matching \'fieldset_end\' block is required for each fieldset_start.<br>
TIP: set label=\'& nbsp;\' to not show the field label.</p>
<p>Parameters:
legend (optional) - adds an optional legend (default = no legend)<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>fieldset_end</strong> <span style="color:red;">new</span></p>
<p>Example: {content_module module=\'ECB2\' field=\'fieldset_end\' label=\'& nbsp;\' block=\'test19fieldsetend\' assign=\'test19fieldsetend\' }<br>
Creates the end of a fieldset for grouping relavant admin fields together. Note: a matching \'fieldset_start\' block is required for each fieldset_end.<br>
TIP: set label=\'& nbsp;\' to not show the field label.
</p><br>



<h3>Upgrade from ECB</h3>
<p>Install ECB2 module and change all "module" parameters, in content_module tags to be module="ECB2" (was "ECB"). Then ECB can be uninstalled.</p><br>


';
?>
