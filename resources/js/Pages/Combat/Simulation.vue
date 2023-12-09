<script setup>
import CombatRaport from '../../Components/Combat/CombatRaport.vue';
import {ref} from 'vue';

let states = ref([]);

function getStates() {
    axios.get(route('combatSimTest')).then(result => {
        states.value = result.data.states;
        console.log(result.data);
    });
}

</script>

<template>
    Combat Sim
    <button @click="getStates">Get test sim</button><br>
    <CombatRaport :states="states" />
</template>

<style lang="scss">

</style>