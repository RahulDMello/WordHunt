<?php header( 'Location: /index.html' ) ;  ?>
<!doctype html>
<html lang="en">
    <head>
        <title>Word Hunt</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        
        <style>
        
            .borderless td, .borderless th {
                border: none;
                height: 3vw;
            }
            
            td {
                text-align: center;
                cursor: pointer;
            }
            
            .highlight {
                color: white;
                background-color: #90949C;
            }
            
            #list {
                width: 100%;
                text-align: center;
            }
            
            img {
                height: 24px;
            }
            
        </style>
        
    </head>
    <body>
    
        <!-- WIN MODAL -->
        <div class="modal fade" id="win-modal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-md">
            <div class="modal-content text-center">
              <h1 class="display-2">YOU WIN</h1>
              <p>Next hunt in 2 secs...</p>
            </div>
          </div>
        </div>
    
        <h6 style="padding: 5px 0px 0px 30px">Find the word - </h6>
        <div class="container">
            <div id="list"></div>
            <table class="table borderless" id="letter-grid">
            </table>
        </div>
        
        <footer class="row-footer">
            <div class="container head-foot">
                <div class="row row-content text-center d-flex align-self-center mr-3" id="me">
                    <div class="btn-group mx-auto social-media"> 
                        <a class="btn" href="http://rahulwordpressdemo-com.stackstaging.com/wordpress/" target="_blank"><img alt="user" class="social-media-icon" src="images/user.png"></img></a>
                        <a class="btn btn-social-icon" href="https://www.facebook.com/anonDfeline" target="_blank"><img class="social-media-icon" src="images/Rahul_DMello_f.png"></img></a>
                        <a class="btn btn-social-icon" href="https://www.linkedin.com/in/rvdmello96/" target="_blank"><img class="social-media-icon" src="images/lin.png"></img></a>
                        <a class="btn btn-social-icon" href="https://twitter.com/anonDfeline" target="_blank"><img class="social-media-icon" src="images/t.png"></img></a> 
                        <a class="btn btn-social-icon" href="https://github.com/anonDfeline" target="_blank"><img class="social-media-icon" src="images/github.png"></img></i></a> 
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
        
        <script src="words.js"></script>
        
        <script>
        
            // TODO : ADD EVENT HANDLERS (also RELOADS) and SOLVE BUGS
        
            // Helper Function(s)
            
            function populateGrid(grid) {
                var grid_html = "";
                
                $.each(grid, function(ind1, value) {
                    grid_html += "<tr>";
                    $.each(value, function(ind2, letter) {
                        grid_html += "<td id="+ind1+"-"+ind2+"> " + letter + " </td>";
                    });
                    grid_html += "</tr>";
                });
                
                $("#letter-grid").html(grid_html);
            }
            
            function populateWordList(list) {
                var list_html = "";
                
                $.each(list, function(ind, value) {
                    list_html += '<h2> "' + value + '" </h2> <br>';
                });
                
                list_html += "";
                $("#list").html(list_html);
            }
            
            function getAnswerIds(startCol, startRow, direction, length) {
                
                var i = startRow;
                var j = startCol;
                var count = 0;
                var lst = [];
                
                while(count < length) {
                    
                    switch(direction) {
                        case 'u':
                            lst.push(i+"-"+j);
                            i--;
                            break;
                        case 'd':
                            lst.push(i+"-"+j);
                            i++;
                            break;
                        case 'l':
                            lst.push(i+"-"+j);
                            j--;
                            break;
                        case 'r':
                            lst.push(i+"-"+j);
                            j++;
                            break;
                        case 'ul':
                            lst.push(i+"-"+j);
                            j--;
                            i--;
                            break;
                        case 'ur':
                            lst.push(i+"-"+j);
                            i--;
                            j++;
                            break;
                        case 'dl':
                            lst.push(i+"-"+j);
                            i++;
                            j--;
                            break;
                        case 'dr':
                            lst.push(i+"-"+j);
                            j++;
                            i++;
                            break;
                    } 
                    count++;
                }
                return lst;
            }
        
        
            // Declaration(s)
            
            var grid = [];
            var words = [];
            var word_ids = [];
            
            
            // Working
            
            $( document ).ready(function() {
            
                // Initialization(s)
                
                words = getWords();
                grid = getWordsGrid(words);
                
                
                // Populate grid and words list
                populateGrid(grid);
                populateWordList(words);
                
                words_ids = getAnswerIds(startCol, startRow, direction[direction_ind], words[0].length)
            
                $(function () {
                    var isMouseDown = false;
                    $(".borderless td")
                        .mousedown(function () {
                            isMouseDown = true;
                            $(this).toggleClass("highlight");
                            return false; // prevent text selection
                        })
                        .mouseover(function () {
                            if (isMouseDown) {
                                $(this).toggleClass("highlight");
                            }
                        })
                        .click(function(){
                            if (isMouseDown) {
                                $(this).toggleClass("highlight");
                            }
                        })
                        .bind("selectstart", function () {
                            return false; // prevent text selection in IE
                        });

                    $(document).mouseup(function () {
                        isMouseDown = false;
                        if($(".highlight").length == words[0].length) {
                            var check = true;
                            $.each(words_ids, function(ind, id){
                                check = check && $("#"+id).hasClass("highlight");
                            })
                            if (check) {
                                $("#win-modal").modal('show');
                                setTimeout(function(){location.reload();}, 2000) // 2 secs
                            }
                        }
                    });
                });
                
            });
            
        </script>
    </body>
</html>