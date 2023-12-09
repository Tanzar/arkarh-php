<script setup>
import CombatRaportUnit from './CombatRaportUnit.vue';
import { ref, watch } from 'vue';

let props = defineProps({
    line: {
        type: Object,

    }
});

let unitsLine = ref({});
parseLine();

watch(props, function() {
    parseLine();
})

function parseLine() {
    for (let i = 0; i < 15; i++) {
        if (props.line[i] !== undefined) {
            unitsLine.value[i] = props.line[i];
        } else {
            unitsLine.value[i] = false;
        }
    }
}

</script>

<template>
    <div class="units-line"> 
        <template v-for="unit in unitsLine">
            <template v-if="unit !== false">
                <CombatRaportUnit :unit="unit" />
            </template>
            <template v-else>
                <CombatRaportUnit/>
            </template>
        </template>
    </div>
</template>

<style lang="scss">

.units-line {
    display: flex;
    flex-direction: row;
    padding: 5px;
    border: 3px rgb(0, 0, 0) solid;
    justify-content: center;
    align-items: center;
}

</style>