<title><?=Title('Entry bulunamadı')?></title>
<style>
    @import url('https://fonts.googleapis.com/css?family=Fira+Mono:400');

    body{
        display: flex;
        width: 100vw;
        height: 100vh;
        align-items: center;
        justify-content: center;
        margin: 0;
        background: #131313;
        color: #fff;
        font-family: 'Fira Mono', monospace;
    }

    div[title="404"]{
        font-size: 96px;
        letter-spacing: -7px;
        animation: glitch 1s linear infinite;
    }

    @keyframes glitch{
        2%,64%{
            transform: translate(2px,0) skew(0deg);
        }
        4%,60%{
            transform: translate(-2px,0) skew(0deg);
        }
        62%{
            transform: translate(0,0) skew(5deg);
        }
    }

    div[title="404"]:before,
    div[title="404"]:after{
        content: attr(title);
        position: absolute;
        left: 0;
    }

    div[title="404"]:before{
        animation: glitchTop 1s linear infinite;
        clip-path: polygon(0 0, 100% 0, 100% 33%, 0 33%);
        -webkit-clip-path: polygon(0 0, 100% 0, 100% 33%, 0 33%);
    }

    @keyframes glitchTop{
        2%,64%{
            transform: translate(2px,-2px);
        }
        4%,60%{
            transform: translate(-2px,2px);
        }
        62%{
            transform: translate(13px,-1px) skew(-13deg);
        }
    }

    div[title="404"]:after{
        animation: glitchBotom 1.5s linear infinite;
        clip-path: polygon(0 67%, 100% 67%, 100% 100%, 0 100%);
        -webkit-clip-path: polygon(0 67%, 100% 67%, 100% 100%, 0 100%);
    }

    @keyframes glitchBotom{
        2%,64%{
            transform: translate(-2px,0);
        }
        4%,60%{
            transform: translate(-2px,0);
        }
        62%{
            transform: translate(-22px,5px) skew(21deg);
        }
    }
</style>

<div title="404">404</div>
<div style=" position: fixed;top: 50px;left: 50%;
      transform: translateX(-50%);width: 100%;
      max-width: 385px;z-index: 998;border-radius: 12px;
      padding: 12px 16px;box-shadow: 0 15px 10px 0 rgb(0 0 0 / 15%);
      border: 1px solid #e50914;
      background: #3009097d;color: #e50914;"><?=$message?></div>