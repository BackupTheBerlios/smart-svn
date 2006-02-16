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
        document.getElementById('searchresult').innerHTML = '<ul>';

	if(result.length == 0)
	{
	    document.getElementById('searchresult').innerHTML += '<li>no result</li>';
	}
	else
	{
	    for (var i = 0; i < result.length; ++i)
	    {
	        document.getElementById('searchresult').innerHTML += '<li><span>';	    
	    	
	    	// build the node branch of an article
	    	for (var b = 0; b < result[i]['nodeBranch'].length; ++b)
	    	{
      	            document.getElementById('searchresult').innerHTML += '<a href="index.php?id_node='+result[i]['nodeBranch'][b]['id_node']+'">'+result[i]['nodeBranch'][b]['title']+'</a>/';	    	
	    	}
      	        
      	        // print the node of an article
      	        document.getElementById('searchresult').innerHTML += '<a href="index.php?id_node='+result[i]['id_node']+'">'+result[i]['node']['title']+'</a>/</span><br />';	    	    	
      	        
      	        // print article link title
      	        document.getElementById('searchresult').innerHTML += '<h4> - <a href="index.php?id_article='+result[i]['id_article']+'">'+result[i]['title']+'</a></h4>';
      	        
      	        document.getElementById('searchresult').innerHTML += '</li>';
      	    }
      	}
      	document.getElementById('searchresult').innerHTML += '</ul>';
      	
      	// reset search button text
      	document.getElementById('dosearch').value = 'search';
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
function doSearch( s ) 
{
    // change search button text
    document.getElementById('dosearch').value = '... please wait';
    
    // Create object with values of the form field
    var objTemp = new Object();
    objTemp['search'] = document.getElementById('search').value;

    // launch remote search
    remoteTest.search(objTemp);
}

// create our remote object
var remoteTest = new ViewArticleAjax(testCallback);