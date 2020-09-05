<?php
function calcDeck($cards) {
    $aces = 0;
    $sum = 0;
    foreach($cards as $c) {
        if($c->val === 1) {
            $aces++;
        }
        $sum += ($c->val === 1 ? 11 : $c->val);
    }
    
    if($aces == 0 || $sum <= 21)
        return $sum;
    
    while($aces-- > 0) {
        $sum -= 10;
        if($sum <= 21)
            return $sum;
    }
    return $sum;
}

function drawCard() {
    $deck = genDeck($_SESSION["seed"]);
    return $deck[$_SESSION["cnt"]++];
}

function returnJSON($obj) {
    header("Content-Type: application/json");
    die(json_encode($obj));
}

$action = isset($_GET["action"]) ? $_GET["action"] : "";

session_start();
if(!isset($_SESSION["r"])) {
    $_SESSION["r"] = true;
    $_SESSION["credit"] = 100;
}

$credit = $_SESSION["credit"];
$seed = $_SESSION["seed"]; 
switch($action) {
    case "start":
        $_SESSION["state"] = 1;
        $_SESSION["seed"] = random_int(1, 2100000000);
        $_SESSION["cnt"] = 0;
        $deck = genDeck($_SESSION["seed"]);
        $_SESSION["player_card"] = array();
        $_SESSION["dealer_card"] = array();
        returnJSON(array("code" => "ok",
                         "credit" => $credit));
        break;
    case "bet":
        if($_SESSION["state"] !== 1)
            returnJSON(array("code" => "error",
                            "msg" => "No hack"));
        $_SESSION["state"] = 2;
        $amount = isset($_GET["amount"]) ? intval($_GET["amount"]) : "";
        if($amount <= 0 || $amount > $credit)
            returnJSON(array("code" => "error",
                             "msg" => "Wrong bet amount"));
        $_SESSION["bet"] = $amount;
        $_SESSION["credit"] -= $amount;
        
        $_SESSION["player_card"][] = drawCard();
        $_SESSION["dealer_card"][] = drawCard();
        $_SESSION["player_card"][] = drawCard();
        $_SESSION["dealer_card"][] = drawCard();
        
        returnJSON(array("code" => "ok",
                         "credit" => $_SESSION["credit"],
                         "player_cards" => $_SESSION["player_card"],
                         "player_sum" => calcDeck($_SESSION["player_card"]),
                         "dealer_card" => $_SESSION["dealer_card"][0],
        ));
        break;
    case "hit":
        if($_SESSION["state"] !== 2)
            returnJSON(array("code" => "error",
                            "msg" => "No hack"));
        $_SESSION["player_card"][] = drawCard();
        if(calcDeck($_SESSION["player_card"]) > 21) {
            $_SESSION["state"] = 4;
            returnJSON(array("code" => "done",
                            "credit" => $_SESSION["credit"],
                            "player_cards" => $_SESSION["player_card"],
                            "player_sum" => calcDeck($_SESSION["player_card"]),
                            "dealer_card" => $_SESSION["dealer_card"][0],
            ));  
        }
        returnJSON(array("code" => "ok",
                "credit" => $_SESSION["credit"],
                "player_cards" => $_SESSION["player_card"],
                "player_sum" => calcDeck($_SESSION["player_card"]),
                "dealer_card" => $_SESSION["dealer_card"][0],
        ));
        break;
    case "stay":
        if($_SESSION["state"] < 2 || calcDeck($_SESSION["player_card"]) > 21)
            returnJSON(array("code" => "error",
                            "msg" => "No hack"));
        $_SESSION["state"] = 3;

        returnJSON(array("code" => "ok",
            "credit" => $_SESSION["credit"],
            "player_cards" => $_SESSION["player_card"],
            "player_sum" => calcDeck($_SESSION["player_card"]),
            "dealer_cards" => $_SESSION["dealer_card"],
            "dealer_sum" => calcDeck($_SESSION["dealer_card"]),
        ));
        break;
    case "deal":
        if($_SESSION["state"] !== 3)
            returnJSON(array("code" => "error",
                            "msg" => "No hack"));
        
        if(calcDeck($_SESSION["dealer_card"]) < 17) { 
            $_SESSION["dealer_card"][] = drawCard();
       }
    
        if(calcDeck($_SESSION["dealer_card"]) >= 17)
             $_SESSION["state"] = 4;
 
        returnJSON(array("code" => "ok",
            "credit" => $_SESSION["credit"],
            "player_cards" => $_SESSION["player_card"],
            "player_sum" => calcDeck($_SESSION["player_card"]),
            "dealer_cards" => $_SESSION["dealer_card"],
            "dealer_sum" => calcDeck($_SESSION["dealer_card"]),
        ));
        break;
    case "blackjack":
        if($_SESSION["state"] !== 2)
            returnJSON(array("code" => "error",
                            "msg" => "No hack"));
        if(calcDeck($_SESSION["player_card"]) !== 21 || sizeof($_SESSION["player_card"]) !== 2)    
            returnJSON(array("code" => "error",
                            "msg" => "No hack"));
        $_SESSION["credit"] += intval(2.5 * $_SESSION["bet"]);
        
        returnJSON(array("code" => "blackjack",
            "earn" => 1.5 * $_SESSION["bet"],
            "credit" => $_SESSION["credit"],
        ));
        break;
    case "done":
        if($_SESSION["state"] !== 4)
            returnJSON(array("code" => "error",
                             "msg" => "No hack"));
        if(calcDeck($_SESSION["dealer_card"]) < 17 && calcDeck($_SESSION["player_card"]) <= 21)
            returnJSON(array("code" => "error",
                            "msg" => "No hack"));
        if(calcDeck($_SESSION["player_card"]) > 21)
            returnJSON(array("code" => "burst",
                "lost" => $_SESSION["bet"],
                "credit" => $_SESSION["credit"],
            ));
        if(calcDeck($_SESSION["player_card"]) > calcDeck($_SESSION["dealer_card"])
           || calcDeck($_SESSION["dealer_card"]) > 21) {
            $_SESSION["credit"] += 2 * $_SESSION["bet"];
            returnJSON(array("code" => "win",
                "earn" => $_SESSION["bet"],
                "credit" => $_SESSION["credit"],
            ));
        }
        if(calcDeck($_SESSION["player_card"]) == calcDeck($_SESSION["dealer_card"])) {
            $_SESSION["credit"] += $_SESSION["bet"];
            returnJSON(array("code" => "draw",
                "earn" => 0,
                "credit" => $_SESSION["credit"],
            ));
        }
        returnJSON(array("code" => "lose",
            "lost" => $_SESSION["bet"],
            "credit" => $_SESSION["credit"],
        ));
}
