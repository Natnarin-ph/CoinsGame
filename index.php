<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Cute+Font&display=swap" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
  <style>
    .txt{
      margin-top: 3cm;
      text-align: center;
      font-size: 20pt;
    }
	
    .win{
      color: teal;
      text-align: center;
      font-size: 20pt;
    }
   
	.topic{
            font-size: 130px;
            font-weight: bold;
            margin-top: 200px;
			font-family: 'Cute Font', cursive;
			text-align:center;
			color:rgb(126, 228, 194);
        }

  </style>
</head>
<body>
  <div class="topic">Coins game</div>
  <div id="app">
    <v-app>
      <v-main>
        <v-container> 
        <div class="txt">
	<?php
    echo "Player:".$_GET["name"];
	?><br>	  
          จำนวนเงินเป้าหมาย  {{goal}}  <br>
          จำนวนเงินในกล่อง  {{currentmoney}}<br> 
          จำนวนเงินที่ AI เติม  {{aimoney}}<br>
        </div>          <br><br>
      
     <div v-if="goal==currentmoney" class="win">
        The End <br>The winner is {{ (!player)? <?php echo $_GET["name"]; ?> : "AI" }} !!<br>
		<a href="index.php?name=<?=$_GET["name"]?>" style="text-decoration:none;"><v-btn class="ma-2" outlined color="rgb(126, 228, 194)">play again</v-btn></a>
		<a href="login.html" style="text-decoration:none;"><v-btn class="ma-2" color="rgb(126, 228, 194)">Home</v-btn></a>
        
	 </div>
     <div v-if="player" style="text-align: center;">
      <!--style="color:rgb(126, 228, 194);height: 3cm; width: 4cm; background-color: rgb(0, 0, 0); margin: 0.5cm;"-->
        <v-btn icon width="auto" @click="addMoney(1)" :disabled="currentmoney+1>goal" style="margin-top:70px;" > <v-avatar size="200"><v-img src="img/1baht.png" style="width: 50;margin-top:20px;" ></v-img></v-avatar></v-btn>
        <v-btn icon width="auto" @click="addMoney(2)" :disabled="currentmoney+2>goal" style="margin-top:70px;"> <v-avatar size="200"><v-img src="img/2baht.png" style="width: 50;margin-top:20px;" ></v-img></v-avatar></v-btn>
        <v-btn icon width="auto" @click="addMoney(5)" :disabled="currentmoney+5>goal" style="margin-top:70px;"> <v-avatar size="200"><v-img src="img/5baht.png" style="width: 50;" ></v-img></v-avatar></v-btn>
    </div>  
    <div v-if="!player" style="text-align: center;">
        <v-btn @click="AIAddMoney()"  style="height: 2cm; width: 3cm; background-color: rgb(126, 228, 194); margin: 0.5cm; border-radius: 10px;">AI เติม </v-btn>
    </div> 
        </v-container>
      </v-main>
    </v-app>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  <script>
    
    
    var scoreTree = new Map();
    function minimax(money,goal,depth,maxmode){  
        var key=money+"-"+maxmode;
        if(scoreTree.has(key)){
            return scoreTree.get(key);
        }
        if(money==goal){
           if(maxmode) return {"score":-1000+depth};
           else return {"score":1000-depth}; 
        }
      var r={};  
      if(maxmode){   
        //MAX  
        var score = -10000;
        var w=0;
        for(const x of [1,2,5]){
           if(money+x<=goal){ 
              var s = minimax(money+x, goal, depth+1, !maxmode); 
//              if(depth==0)
//                console.log(x, s, maxmode);
              if(s.score>score){
                 w=x; 
                 score = s.score; 
              }
           }
        } 
        r={"score":score,"money":w};       
      }else{
        // MIN   
        var score = 10000;
        var w=0;
        for(const x of [1,2,5]){
           if(money+x<=goal){ 
              s = minimax(money+x, goal, depth+1, !maxmode); 
              if(s.score<score){
                 score = s.score; 
                 w=x;
              }
           }
        } 
        r = {"score":score,"money":w};  
      }
      scoreTree.set(key,r);
      return r;  
    }

    //var result = minimax(0,10,0,true);

    new Vue({       
      el: '#app',
      vuetify: new Vuetify(),
      data :{
        goal : 21,
        currentmoney:0,
        aimoney :0,
        player  :true,
      },
      methods :{
        addMoney(x){
          if(x+this.currentmoney <= this.goal){
             this.currentmoney += x;  
             this.player = false;
          }
        },
        AIAddMoney(){
           scoreTree.clear();
           var solution = minimax(this.currentmoney,this.goal,0,true);
           this.aimoney = solution.money;
           this.currentmoney += this.aimoney;    
           this.player = true;
        }
      }
    })


  </script>
</body>
</html>