</td>
            <td width="14" background="/adm/img/bg_main_right.jpg" style="background-repeat:no-repeat;">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr height="3px">
    <td bgcolor="#CACACA"></td>
  </tr>
  <tr height="62px">
    <td bgcolor="#E3E3E3" align="center" valign="middle" class="tahoma_11_grey">&copy;
     2000 - <?=date("Y")?> &nbsp;
      <a href="http://swslive.com" class="tahoma_11_grey">Superior Web Solutions</a></td>
  </tr>
</table>

<script language="javascript" type="text/javascript">
<!--
function showHideCategories(tableID,url) {
	var currentClass=document.getElementById('td'+tableID).className;
	if (url=='') {		
		if (currentClass=='menuItem') {
			document.getElementById('div'+tableID).style.display='';
			document.getElementById('td'+tableID).className='menuItem activeItemSubcat';
		} else {
			document.getElementById('div'+tableID).style.display='none';
			document.getElementById('td'+tableID).className='menuItem';
		}
	} else {
		if (currentClass=='menuItem') {
			document.location=url;
		} else {
			return false;
		}
	}
}
function FillSelect(optionsArr,fieldID,selectValue)
{
	var valuesArr;
	var i, j;
	var objOption;
	var changeField = document.getElementById(fieldID);
	
	try
	{
		while(changeField.removeChild(changeField.firstChild));
	}
	catch(e){}
	
	objOption = document.createElement("option");
	objOption.value = "";
	objOption.appendChild(document.createTextNode("Select"));
	changeField.appendChild(objOption);
	
	for(j=0; j<optionsArr.length; ++j)
	{
		valuesArr = optionsArr[j].split('#');
			try
			{
				objOption = document.createElement("option");
				objOption.value = valuesArr[0];
				objOption.appendChild(document.createTextNode(valuesArr[1]));
				changeField.appendChild(objOption);
				if(selectValue==valuesArr[0])objOption.selected=true;
			}
			catch(e){}
	}
} 


function AddEditPopUp(pageAddress,vars) {
	var fullUrlPath=pageAddress+vars;
	new_window = window.open(fullUrlPath, "Add_Edit",
	"top=30,left=90,toolbar=no, height=300, width=500, location=no,status=yes,menubar=no,scrollbars=yes,resizable=yes");
	new_window.focus();
}
//-->
</script>
</body>
</html>