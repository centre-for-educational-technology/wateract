<style>
.mainBodyWrapper{
    position: relative;
}
.mainBodyWrapper-1 {
    background: no-repeat url(/images/Allikad-1-2.png) 50% / 100%;
}
.mainBodyWrapper-2-1 {
    background: no-repeat url(/images/Allikad-2-1.png) 50% / 100%;
}
.mainBodyWrapper-2-2 {
    background: no-repeat url(/images/Allikad-2-2.png) 50% / 100%;
}
.mainBodyWrapper-3-1 {
    background: no-repeat url(/images/Allikad-3-1.png) 50% / 100%;
}
.mainBodyWrapper-3-2 {
    background: no-repeat url(/images/Allikad-3-2.png) 50% / 100%;
}
.mainBodyWrapper-4-1 {
    background: no-repeat url(/images/Allikad-4-1.png) 50% / 100%;
}
.mainBodyWrapper-4-2 {
    background: no-repeat url(/images/Allikad-4-2.png) 50% / 100%;
}
.mainBodyWrapper > img{
    vertical-align: top;
    width: 100%; /* max width */
    opacity: 0;  /* make it transparent */
}
.mainBodyWrapper > div.main{
    position: absolute;
    top: 0;
    width: 100%;
    height: 100%;
}
.map_container {
    position: fixed;
    top:0;
    left:0;
    right:0;
    bottom:0;
    width:auto;
    height:300px;
    margin:auto;
}
</style>
<template>
    <app-layout>

        <template #header>
            <h2 class="uppercase text-center">Otsime allikaid</h2>
            <!--<div class="flex w-full">
                <div class="w-2/5"></div>
                <h2 class="w-2/5 font-bold uppercase text-xl text-gray-800 ml-20">
                    Otsime allikaid
                </h2>
                <div class="w-1/5"></div>
            </div>-->
        </template>


        <div class="mainBodyWrapper mainBodyWrapper-1">
            <img src="/images/Allikad-1-2.png" />
            <div class="main grid grid-cols-12 ">
                    <div class="col-span-3  p-10">
                        <a href="/springs">Vaata eesti allikate kaarti</a></div>
                <div class="col-span-5"></div>
                <div class="col-span-4  p-10 text-right">
                    <a v-if="$page.user" href="/springs/create">Lisa allikas kaardile</a>
                </div>
            </div>

        </div>
        <div class="mainBodyWrapper mainBodyWrapper-2-1">
            <img src="/images/Allikad-2-1.png" />
            <div class="main">

            </div>
        </div>

        <!--<div class="bg-gray-500 border" style="margin-top:-200px;margin-bottom:-200px;">
            <div  style="height:400px;" class="bg-red-500 border"></div>
        </div>-->

        <div class="mainBodyWrapper mainBodyWrapper-2-2">
            <img src="/images/Allikad-2-2.png" />
            <div class="main">

            </div>
        </div>

        <div class="mainBodyWrapper mainBodyWrapper-3-1">
            <img src="/images/Allikad-3-1.png" />
            <div class="main grid grid-cols-12 block h-15">
                <div class="col-span-3 "></div>
                <div class="col-span-7  ml-3 pt-5 lg:mt-10 min-h-full">
                    <h1 class="text-center text-l sm:text-xl lg:text-2xl uppercase pt-5 pb-2 font-bold">Miks me uurime allikaid</h1>
                </div>
                <div class="col-span-2 "></div>
            </div>
        </div>

        <div class="mainBodyWrapper mainBodyWrapper-3-2 h-400">
            <img src="/images/Allikad-3-2.png"/>
            <div class="main block grid grid-cols-12 min-h-full">
            <div class="col-span-3 "></div>
            <div class="col-span-6  ml-3 border lg:-mt-56">
                <div>
                    Allikas (ka läte) on koht, kus põhjavesi voolab maapinnale.

                    Allikad võivad avaneda ka veekogude põhja ja olla mõnel juhul veekogu peamisteks veega toitjateks.
                    <br /><br />
                    Allikad võivad voolata maapinnale rahulikult või surveliselt. Survelist allikavett nimetatakse arteesiaveeks.

                    Põhjavees on tihti lahustunud palju mineraale, mis on dissotsieerunud ioonideks. Kui vesi väljub maapinnale, siis ta temperatuur tõuseb ja rõhk väheneb ning sellega seoses halveneb temas gaaside lahustuvus. Seetõttu eraldub pinnale jõudnud põhjaveest näiteks süsinikdioksiidi, mistõttu väheneb vee happesus. (See on seotud sellega, et süsinikdioksiid moodustab veega reageerides süsihappe, mis vees dissotsieerudes vabastab sinna hüdrooniumioone.) See viib omakorda näiteks kaltsiumkarbonaadi sadestumiseni, mis moodustab karbonaatse vee korral levinud allikatega seotud sette allikalubja.
                    <br /><br />
                    Suure ioonidesisaldusega põhjavett võidakse kasutada ravi- või joogiveena.
                </div>
                <div class="flex pt-10">
                    <div class="flex-1 border-2 mr-10" style="height:200px">Kaardi info</div>
                    <div class="flex-1 border-2" style="height:200px">Kasutajate info</div>
                </div>
            </div>
            <div class="col-span-3 "></div>
            </div>
        </div>

        <div class="mainBodyWrapper mainBodyWrapper-4-1">
            <img src="/images/Allikad-4-1.png" />
            <div class="main grid grid-cols-12 ">
                <div class="col-span-3 "></div>
                <div class="col-span-7  ml-3 mr-10">
                    <h1 class="text-center text-2xl uppercase pt-5 pb-2 font-bold ">Otsime allikaid</h1>
                    <div>
                        Allikas (ka läte) on koht, kus põhjavesi voolab maapinnale.

                        Allikad võivad avaneda ka veekogude põhja ja olla mõnel juhul veekogu peamisteks veega toitjateks.
                        <br /><br />
                        Allikad võivad voolata maapinnale rahulikult või surveliselt. Survelist allikavett nimetatakse arteesiaveeks.

                        Põhjavees on tihti lahustunud palju mineraale, mis on dissotsieerunud ioonideks. Kui vesi väljub maapinnale, siis ta temperatuur tõuseb ja rõhk väheneb ning sellega seoses halveneb temas gaaside lahustuvus. Seetõttu eraldub pinnale jõudnud põhjaveest näiteks süsinikdioksiidi, mistõttu väheneb vee happesus. (See on seotud sellega, et süsinikdioksiid moodustab veega reageerides süsihappe, mis vees dissotsieerudes vabastab sinna hüdrooniumioone.) See viib omakorda näiteks kaltsiumkarbonaadi sadestumiseni, mis moodustab karbonaatse vee korral levinud allikatega seotud sette allikalubja.
                        <br /><br />
                        Suure ioonidesisaldusega põhjavett võidakse kasutada ravi- või joogiveena.
                    </div>
                </div>
                <div class="col-span-2 "></div>
            </div>
        </div>

        <div class="mainBodyWrapper mainBodyWrapper-4-2">
            <img src="/images/Allikad-4-2.png" />
            <div class="main grid grid-cols-12 ">
                <div class="col-span-3 "></div>
                <div class="col-span-7  ml-3 mr-10">
                    <div class="text-xl font-bold">Lisainfo:</div>
                    <div>
                        Allikas (ka läte) on koht, kus põhjavesi voolab maapinnale.

                        Allikad võivad avaneda ka veekogude põhja ja olla mõnel juhul veekogu peamisteks veega toitjateks.
                        <br /><br />
                        Allikad võivad voolata maapinnale rahulikult või surveliselt. Survelist allikavett nimetatakse arteesiaveeks.

                        Põhjavees on tihti lahustunud palju mineraale, mis on dissotsieerunud ioonideks. Kui vesi väljub maapinnale, siis ta temperatuur tõuseb ja rõhk väheneb ning sellega seoses halveneb temas gaaside lahustuvus. Seetõttu eraldub pinnale jõudnud põhjaveest näiteks süsinikdioksiidi, mistõttu väheneb vee happesus. (See on seotud sellega, et süsinikdioksiid moodustab veega reageerides süsihappe, mis vees dissotsieerudes vabastab sinna hüdrooniumioone.) See viib omakorda näiteks kaltsiumkarbonaadi sadestumiseni, mis moodustab karbonaatse vee korral levinud allikatega seotud sette allikalubja.

                    </div>
                </div>
                <div class="col-span-2 "></div>
            </div>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout'

export default {
    components: {
        AppLayout,
    }
}
</script>
