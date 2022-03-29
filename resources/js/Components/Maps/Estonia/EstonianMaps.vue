<template>

    <div class="h-500 lg:h-600 2xl:h-700">

        <div class="z-depth-1-half map-container block h-full" >

            <OpenStreetMap v-if="openStreetMap" :view="view" :springs="springs" :spring="spring" :cluster="true"></OpenStreetMap>

            <MaaAmetTileMap v-if="maaAmetTileMap" :view="view" :springs="springs" :spring="spring" :cluster="true"></MaaAmetTileMap>

            <MaaAmetWmsMap v-if="maaAmetWmsMap" :view="view" :springs="springs" :spring="spring"></MaaAmetWmsMap>

        </div>

    </div>

</template>

<script>
import OpenStreetMap from "./OpenStreetMap";
import MaaAmetTileMap from "./MaaAmetTileMap";
import MaaAmetWmsMap from "./MaaAmetWmsMap";
import { latLng } from "leaflet";
import { springLocationIcon, confirmedSpringIcon, submittedSpringIcon, notASpringIcon, needsAttentionSpringIcon } from '../../../constants.js';

export default {
    components: {
        OpenStreetMap,
        MaaAmetTileMap,
        MaaAmetWmsMap,
    },
    props: ['springs', 'spring', 'view', 'cluster', 'spring_location'],
    data() {

        let markers = this.getMapMarkers(this.springs);
        let springLocation = {lat: null, lng: null};
        if (this.spring) {
            springLocation = {lat: this.spring.latitude, lng: this.spring.longitude}
        }
        if (this.spring_location) {
            springLocation = this.spring_location;
        }
        let map = 'maaamet';
        let ee_spring = true;
        if (this.spring && this.spring.country !== 'EE') {
            map = 'openstreet';
            ee_spring = false;
        }
        let mapLayersMapping = {
            "Muinsuskaitsealused allikad": 'Kult_allikad',
            "Loodusdirektiivi allikaelupaigad": 'LD_allikad',
            "Pärandkultuuriallikad": 'Par_allikad',
            "Allikate seirejaamad": 'Seire_allikad',
            "Allikalised vääriselupaigad": 'VEP_allikad',
            "Looduskaitsealused allikad": 'UOB_allikad',
            "Ürglooduse raamatu allikad": 'Urg_allikad',
            "Looduslike pühapaikade allikad": 'Lood_puha_allikad',
            "Kohapärimuse allikad": 'Koha_allikad',
        };

        return {
            mapOptions: {
                tap: this.mapTap(),
                zoomSnap: 1,
            },

            cacheOpenStreetMapZoom: 7,
            cacheMaaAmetMapZoom: 3,

            openStreetMapZoom: 7,
            maaAmetMapZoom: 3,

            openStreetMap: map !== 'maaamet',
            maaAmetMap: map !== 'openstreet',

            mapCenter: latLng(58.379, 24.554),

            maaAmetTileMap: map !== 'openstreet',
            maaAmetWmsMap: false,
            maaAmetMapType: 'relief',       // relief, relief_shaded, orthophoto

            ee_spring: ee_spring,

            liveLocation: false,
            currentPosition: {lat: null, lng: null},

            springLocation: springLocation,
            springLocationIcon: springLocationIcon,

            fullscreen: false,
            mapMarkers: markers,
            mapLayers: [],
            mapLayersMapping: mapLayersMapping,
        }
    },
    methods: {
        mapTap() {
            if ( L.Browser.safari && !L.Browser.mobile) {
                return false;
            }
            return true;
        },
        showWorldMap() {
            this.maaAmetTileMap = false;
            this.maaAmetWmsMap = false;
            this.openStreetMap = true;
            this.openStreetMapZoom = this.cacheOpenStreetMapZoom;
        },
        showMaaAmetMap() {
            this.openStreetMap = false;
            if (this.maaAmetMapZoom > 13) {
                this.maaAmetTileMap = false;
                this.maaAmetWmsMap = true;
            } else {
                this.maaAmetTileMap = true;
                this.maaAmetWmsMap = false;
            }
            this.maaAmetMapZoom = this.cacheMaaAmetMapZoom;
        },
        openStreetShowLocation() {
            this.openStreetMapObject.locate();
        },
        openStreetOnLocationFound(location) {
            this.currentPosition= location.latlng;
            this.openStreetMapObject.setView(location.latlng, 9);
            this.leafletMapObject.setView(location.latlng, 9);
        },
        showLocation() {
            this.leafletMapObject.locate();
        },
        setCurrentPosition(location) {
            this.liveLocation = true;
            this.currentPosition= location.latlng;
        },
        maaAmetMapTypeUpdate(type) {
            this.maaAmetMapType = type;
        },
        maaametZoomUpdate(zoom) {
            this.maaAmetMapZoom = zoom;
            if (zoom > 13) {
                this.maaAmetTileMap = false;
                this.maaAmetWmsMap = true;
            } else {
                this.maaAmetTileMap = true;
                this.maaAmetWmsMap = false;
            }
            let new_openstreet_zoom = zoom + 4;
            this.cacheMaaAmetMapZoom = zoom;
            if ( this.cacheOpenStreetMapZoom !== new_openstreet_zoom ) {
                this.cacheOpenStreetMapZoom = new_openstreet_zoom;
            }
        },
        openStreetMapZoomUpdate(zoom) {
            let new_maaamet_zoom = zoom - 4;
            this.cacheOpenStreetMapZoom = zoom;
            if (this.cacheMaaAmetMapZoom !== new_maaamet_zoom) {
                this.cacheMaaAmetMapZoom = new_maaamet_zoom;
            }
        },
        mapCenterUpdate(center) {
            this.mapCenter = center;
        },
        maaametFullscreenChanged(fullscreen) {
            //console.log(fullscreen);
            //console.log("muudatus");
            /*if (this.leafletMapObject.isFullscreen()) {
                this.fullscreen = true;
            } else {
                this.fullscreen = false;
            }*/
        },
        fullscreenUpdate(map) {
            //console.log(map.isFullscreen());
            //console.log("muudatus");
            /*if (this.openStreetMapObject.isFullscreen()) {
                this.fullscreen = true;
            } else {
                this.fullscreen = false;
            }*/
        },
        mapLayersAdd(layer) {
            this.mapLayers.push(this.mapLayersMapping[layer]);
        },
        mapLayersRemove(layer) {
            this.mapLayers.pop(this.mapLayersMapping[layer]);
        },
        updateLocation(location) {
            if (this.view === 'create' || this.view === 'edit') {
                let latitude = Number(location.latlng.lat);
                let longitude = Number(location.latlng.lng);
                if (latitude && longitude) {
                    this.springLocation = {lat: latitude, lng: longitude};
                    this.$emit('changeLocation', location);
                }
            }
        },
        getMapMarkers(springs) {
            let markers = [];
            _.forEach(springs, function(spring) {
                let springIcon = confirmedSpringIcon;
                let springStatus = spring.status;
                if (spring.not_a_spring) {
                    springIcon = notASpringIcon;
                    springStatus = "not_a_spring";
                } else if (spring.needs_attention) {
                    springIcon = needsAttentionSpringIcon;
                    springStatus = "needs_attention";
                } else if (spring.status === 'submitted') {
                    springIcon = submittedSpringIcon;
                }
                markers.push({
                    id: spring.code,
                    name: spring.name,
                    status: springStatus,
                    position: latLng(spring.latitude, spring.longitude),
                    icon: springIcon,
                });
            });
            return markers;
        },
        getExistingSprings() {
            let params = {};
            if (this.spring) {
                params = {
                    'spring_id': this.spring.id
                }
            }
            axios.get('/getSprings', { params }).then(response => {
                let springs = response.data;
                this.mapMarkers = this.getMapMarkers(springs);
            })
        }
    },
    created: function(){
        if (this.spring || this.view === 'create' || this.view === 'landing_page') {
            this.getExistingSprings();
        }
    }
}
</script>
