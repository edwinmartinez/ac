{include file="adminHeader.tpl"}

    <div id="mailList">
    {* accessing key the PHP syntax alternate *}
    <table id="mailTable">
        <tr>
            <td>row</td> <td></td><td>id</td><td>from user</td><td>to user</td><td>Subject</td><td>Date</td><td>Message ip</td>
        </tr>
            <tr>
                {if is_array($mails)}
            {foreach $mails as $mail}
                            <tr class="mailRow">
                            <td>{$mail@iteration}</td>
                            <td class="mailCell checkBoxCell"><input name="mid[]" type="checkbox" value="{$mail.privmsgs_id}" /></td>
                            <td class="mailCell openInfoCell"><a class="openInfo"  href="#" id="openInfo-{$mail.privmsgs_id}">{$mail.privmsgs_id}</a></td>
                            <td class="mailCell uidCell"><a class="userNameLink" href="/{$mail.from_username}">{$mail.from_username}</a></td>
                            <td class="mailCell usernameCell"><a class="userNameLink" href="/{$mail.to_username}">{$mail.to_username}</a></td>
                            <td class="mailCell">{$mail.privmsgs_subject}</td>
                            <td class="mailCell">{$mail.message_date}</td>
                            <td class="mailCell">{$mail.message_ip}</td>
                            <tr class="messageRow" id="ir-{$mail.privmsgs_id}"><td colspan="6"><div id="irdiv-{$mail.privmsgs_id}">{$mail.message_text}</div></td></tr>
                            </tr>
            {/foreach}
            {else}
                {$mail}
            {/if}
            </tr>
    </table>
    </div>

{include file="adminFooter.tpl"}