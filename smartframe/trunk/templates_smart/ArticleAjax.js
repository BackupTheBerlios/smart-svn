  /**
   * register callback functions
   */
  var testCallback = {
    simpleText: function(result) 
    {
      document.getElementById('simpletext').innerHTML += '<p>'+result+'</p>';
    },
    showAlertBox: function() 
    {
    },
    calculate: function(result) 
    {
      document.getElementById('result').innerHTML = result;
    },  
    search: function(result) 
    {
        document.getElementById('searchresult').innerHTML = '';

	if(result == 0)
	{
	    document.getElementById('searchresult').innerHTML = 'Search field is empty';
	    return;
	}
        
	for (var i = 0; i < result.length; ++i)
	{
      	    document.getElementById('searchresult').innerHTML += '- <a href="index.php?id_article='+result[i]['id_article']+'">'+result[i]['title']+'</a><br />';
      	}
    }   
  }

/**
* Calculate function
*/
function doCalculation() 
{
    // Create object with values of the form
    var objTemp = new Object();
    objTemp['number1'] = document.getElementById('number1').value;
    objTemp['number2'] = document.getElementById('number2').value;

    remoteTest.calculate(objTemp);
}

/**
* Search function
*/
function doSearch() 
{
    // Create object with values of the form
    var objTemp = new Object();
    objTemp['search'] = document.getElementById('search').value;

    remoteTest.search(objTemp);
}

// create our remote object
var remoteTest = new ViewArticleAjax(testCallback);