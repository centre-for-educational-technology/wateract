
import { icon } from "leaflet";

export const relief_shaded_layers = [
    {
        name: 'reljeef',
        url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/reljeef/{z}/{x}/{y}.png&ASUTUS=TLU&KESKKOND=ALLIKAD',
        zindex: 3,
        maxzoom: 10,
    },
    {
        name: 'hybrid',
        url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/kaart/{z}/{x}/{y}.png&ASUTUS=TLU&KESKKOND=ALLIKAD',
        zindex: 1,
        maxzoom: 10,
    },
    {
        name: 'pohi',
        url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/epk_vv/{z}/{x}/{y}.png&ASUTUS=TLU&KESKKOND=ALLIKAD',
        zindex: 2,
        maxzoom: 14,
        attribution: "Reljeefvarjutusega põhikaart, <a href='http://www.maaamet.ee'>Maa-amet</a>"
    },
];

export const relief_layers = [
    {
        name: 'reljeef',
        url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/vreljeef/{z}/{x}/{y}.png&ASUTUS=TLU&KESKKOND=ALLIKAD',
        zindex: 2,
        maxzoom: 13,
        attribution: "Värviline reljeefvarjutus, <a href='http://www.maaamet.ee'>Maa-amet</a>"
    },
    {
        name: 'hybrid',
        url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/hybriid/{z}/{x}/{y}.png&ASUTUS=TLU&KESKKOND=ALLIKAD',
        zindex: 3,
        maxzoom: 13,
    },
];

export const orthophoto_layers = [
    {
        name: 'hybrid',
        url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/hybriid/{z}/{x}/{y}.png&ASUTUS=TLU&KESKKOND=ALLIKAD',
        zindex: 2,
        maxzoom: 13,
    },
    {
        name: 'foto',
        url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/foto/{z}/{x}/{y}.png&ASUTUS=TLU&KESKKOND=ALLIKAD',
        zindex: 1,
        maxzoom: 14,
        attribution: "Ortofoto, <a href='http://www.maaamet.ee'>Maa-amet</a>"
    },
];

export const relief_wms_layers = [
    {
        name: 'Relief',
        zindex: 1,
        visible: true,
        format: 'image/png',
        layers: 'vreljeef,HYBRID',
        attribution: "Värviline reljeefvarjutus, <a href='http://www.maaamet.ee'>Maa-amet</a>"
    },
];

export const relief_shaded_wms_layers = [
    {
        name: 'ReljeefShaded',
        zindex: 1,
        visible: true,
        format: 'image/png',
        layers: 'pohi_vv',
        attribution: "Reljeefvarjutusega põhikaart, <a href='http://www.maaamet.ee'>Maa-amet</a>"
    },
];

export const orthophoto_wms_layers = [
    {
        name: 'Orthophoto',
        zindex: 1,
        visible: true,
        format: 'image/png',
        layers: 'EESTIFOTO',
        attribution: "Ortofoto, <a href='http://www.maaamet.ee'>Maa-amet</a>"
    },
];

export const springLocationIcon = icon({
    iconUrl: '/images/marker-icon-green.png',
    shadowUrl: '/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    shadowSize: [41, 41]
});

let redDotSvgString = '<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" stroke="black" stroke-width="10" fill="red"/></svg>';
let redDotIconUrl = encodeURI("data:image/svg+xml," + redDotSvgString).replace('#','%23');
export const currentPositionIcon = icon({
    iconUrl: redDotIconUrl,
    iconSize: [16, 16],
    iconAnchor: [8, 16]
});

export const confirmedSpringIcon = icon({
    iconUrl: '/images/marker-icon-blue.png',
    shadowUrl: '/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    shadowSize: [41, 41]
});

export const submittedSpringIcon = icon({
    iconUrl: '/images/marker-icon-orange.png',
    shadowUrl: '/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    shadowSize: [41, 41]
});

export const notASpringIcon = icon({
    iconUrl: '/images/marker-icon-grey.png',
    shadowUrl: '/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    shadowSize: [41, 41]
});

export const needsAttentionSpringIcon = icon({
    iconUrl: '/images/marker-icon-red.png',
    shadowUrl: '/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    shadowSize: [41, 41]
});
