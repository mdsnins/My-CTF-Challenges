$(function() {

let player_cards = [], player_sum = 0;
let dealer_cards = [], dealer_sum = 0;
function start() {
    $.get("./blackjack.php?action=start")
    .done(function(r) {
        credit = r.credit;
        let t = prompt(`Bet Amount : 1~${credit}`);
        t = parseInt(t);

        if(isNaN(t) || t > credit) {
            alert("No");
            return false;
        }

        bet(t);
        $("#ingame").show();
    });
}

function bet(amount) {
    $.get(`./blackjack.php?action=bet&amount=${amount}`)
    .done(function(r) {
        if(r.code == "error") {
            alert(r.msg);
            return false;
        }
        credit = r.credit;
        player_cards = r.player_cards;
        player_sum = r.player_sum;
        dealer_cards.push(r.dealer_card);
        update();

        if(player_sum == 21) {
            blackjack();
        }
    });
}

function hit() {
    $.get('./blackjack.php?action=hit')
    .done(function(r) {
        if(r.code == "error") {
            alert(r.msg);
            return false;
        }
        credit = r.credit;
        player_cards = r.player_cards;
        player_sum = r.player_sum;
        update(1);

        if(r.code == "done") {
            done();
        }
        if(player_sum == 21) {
            stay();
        }
    })
}

function stay() {
    $.get('./blackjack.php?action=stay')
    .done(function(r) {
        if(r.code == "error") {
            alert(r.msg);
            return false;
        }
        credit = r.credit;
        player_cards = r.player_cards;
        player_sum = r.player_sum;
        dealer_cards = r.dealer_cards;
        dealer_sum = r.dealer_sum;
        update(-1);
        
        let intv = setInterval(function() {           
            deal();
            setTimeout(function() { if(dealer_sum >= 17) {
                clearInterval(intv);
                done();
                return;
            } },  500);
        }, 1500);
    });
}

function deal(intv) {
    $.get('./blackjack.php?action=deal')
    .done(function(r) {
        if(r.code == "error") {
            alert(r.msg);
            return false;
        }
        credit = r.credit;
        player_cards = r.player_cards;
        player_sum = r.player_sum;
        dealer_cards = r.dealer_cards;
        dealer_sum = r.dealer_sum;
        if(dealer_cards.length > 2)
            update(-1);
    });
}

function done() {
    $.get('./blackjack.php?action=done')
    .done(function(r) {
        if(r.code == "error") {
            alert(r.msg);
            return false;
        }
        credit = r.credit;
        switch(r.code) {
            case "burst":
                alert(`Bursted, lost ${r.lost} :P`);
                break;
            case "win":
                alert(`You win, earned ${r.earn}`);
                break;
            case "draw":
                alert("Draw!");
                break;
            case "lose":
                alert(`You lose, lost ${r.lost} :(`);
                break;
        }
        fin(); 
    });
}
function blackjack() {
    $.get('./blackjack.php?action=blackjack')
    .done(function(r) {
        if(r.code == "error") {
            alert(r.msg);
            return false;
        }
        credit = r.credit;
        alert(`Blackjack🎉, earned ${r.earn}`);
        fin();
    });
}
function fin() {
    $("#credit").text(credit);
    $("#ingame").hide();
    $("#dealer").html('');
    $("#player").html('');
    player_cards = [], player_sum = 0;
    dealer_cards = [], dealer_sum = 0;
}

function appendImage(ctx, suit, rank) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'render.php', true);
    xhr.responseType = 'blob';
    xhr.setRequestHeader('Content-Type', 'text/xml');
    
    xhr.onload = function(e) {
        if(this.status == 200) {
            let blob = this.response;
            console.log(blob);
            ctx.append(`<img src='${window.URL.createObjectURL(blob)}' />`);
        }
    };
    xhr.send(`<xml><suit>${suit}</suit><rank>${rank}</rank><meta><width>150</width><height>200</height><type>jpeg</type><mime>image/jpeg</mime></meta></xml>`);

}

function update(latest = 0) {
    //console.log(`credit: ${credit}`);
    console.log(`dealer_cards:`, dealer_cards);
    console.log(`player_cards:`, player_cards);

    if(!latest) {
        dealer_cards.forEach(function(card) {
            appendImage($("#dealer"), card.suit, card.rank);
        });
        player_cards.forEach(function(card) {
            appendImage($("#player"), card.suit, card.rank);
        });
    }
    else if(latest == -1) {
        let card = dealer_cards[dealer_cards.length - 1];
        appendImage($("#dealer"), card.suit, card.rank);
    }
    else if(latest == 1) {
        let card = player_cards[player_cards.length - 1];
        appendImage($("#player"), card.suit, card.rank);
    }
   
}

$("#ingame").hide();
$("#start").click(start);
$("#hit").click(hit);
$("#stay").click(stay);
$("#credit").text(credit);
});
