<script setup>
import CombatRaportLine from './CombatRaportLine.vue';


let props = defineProps({
    state: {
        type: Object,
        default() {
            return {
                front: {},
                back: {},
                graveyard: {},
                reserves: {}
            }
        }
    },
    reversed: {
        type: Boolean,
        default: false,
    }
});

</script>

<template>
    <div class="side">
        <div class="side-row">
            <CombatRaportLine :line="(reversed) ? state.front : state.back" />
            <div class="side-units">
                
            </div>
        </div>
        <div class="side-row">
            <CombatRaportLine :line="(reversed) ? state.back : state.front" />
            <div class="side-units">
                
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>

.side {
    display: flex;
    flex-direction: column;
}

.side-row {
    display: flex;
    flex-direction: row;
}

.side-line {
    display: flex;
    flex-direction: row;
    width: fit-content;
    height: fit-content;
    padding: 5px;
}

.side-units {
    display: flex;
    flex-direction: row;
    width: 300px;
    height: 70px;
    border: 3px rgb(0, 0, 0) solid;
}

</style>