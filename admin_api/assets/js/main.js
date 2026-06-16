      window.addEventListener("DOMContentLoaded", () => Alpine.start());
      


$(document).ready(function(){
  $("#showtoggle").click(function(){
    $("#tog").toggle();
  });
});

$(document).ready(function(){
  $("#showtoggle2").click(function(){
    $("#tog2").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle1").click(function(){
    $("#tog1").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle3").click(function(){
    $("#tog3").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle4").click(function(){
    $("#tog4").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle6").click(function(){
    $("#tog6").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle5").click(function(){
    $("#tog5").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle7").click(function(){
    $("#tog7").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle8").click(function(){
    $("#tog8").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle9").click(function(){
    $("#tog9").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle10").click(function(){
    $("#tog10").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle11").click(function(){
    $("#tog11").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle12").click(function(){
    $("#tog12").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle13").click(function(){
    $("#tog13").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle14").click(function(){
    $("#tog14").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle15").click(function(){
    $("#tog15").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle16").click(function(){
    $("#tog16").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle17").click(function(){
    $("#tog17").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle18").click(function(){
    $("#tog18").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle19").click(function(){
    $("#tog19").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle20").click(function(){
    $("#tog20").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle21").click(function(){
    $("#tog21").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle22").click(function(){
    $("#tog22").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle23").click(function(){
    $("#tog23").toggle();
  });
});
$(document).ready(function(){
  $("#showtoggle24").click(function(){
    $("#tog24").toggle();
  });
});

$(document).ready(function(){
  $("#showtoggle25").click(function(){
    $("#tog25").toggle();
  });
});

$(document).ready(function(){
  $("#showtoggle26").click(function(){
    $("#tog26").toggle();
  });
});






// login page js
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
});



var myApp = angular.module('myApp',[]);

myApp.controller('dropdownCtrl', ['$scope','$rootScope', function($scope, $rootScope) {
  $scope.listVisible = false;
  $scope.isPlaceholder = true;                                 
                                  
  $scope.show = function() {
    event.stopPropagation();
    $scope.listVisible = !$scope.listVisible;
  };
  $scope.select = function(item) {
    $scope.selectedDate = item;
  };
  angular.element(document).on("click", function(e) {
    $rootScope.$broadcast("documentClicked", angular.element(e.target));
  });
                                  
  $rootScope.$on("documentClicked", function(inner, target) {
      if (!$(target[0]).is(".dropdown-display.activated") && !$(target[0]).parents(".dropdown-display.activated").length > 0)
          $scope.$apply(function() {
            $scope.listVisible = false;
          });
      });
                                         

    $scope.monthShowArray = [{
      'name': '1',
      'date':'2016-08-08'
    },{
      'name': '2',
      'date':'2016-08-09'
    },{
      'name': '3',
      'date':'2016-08-10'
    }]

    //选中的日期
    $scope.selectedDate = $scope.monthShowArray[0];                             
}]);


































