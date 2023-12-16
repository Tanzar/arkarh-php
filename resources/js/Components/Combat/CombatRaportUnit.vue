<script setup>

let props = defineProps({
    unit: {
        type: Object,
        default() {
            return {
                icon: '',
                target: false,
                source: false,
                health: 1,
                morale: 1,
            };
        }
    }
});

function unitClick(unit) {
    console.log(unit);
}

</script>

<template>
    <div 
        class="unit" 
        :class="{ target: unit.target && !unit.source, source: unit.source }" 
        @click="unitClick(unit)"
    >
        <img 
            class="icon" 
            v-if="unit.icon !== undefined && unit.icon !== ''" 
            :src="unit.icon"
        />
    </div>
</template>

<style lang="scss" scoped>

.icon {
    width: 50px;
    height: 50px;
    min-width: 50px;
    min-height: 50px;
}

.unit {
    width: 50px;
    height: 50px;
    min-width: 50px;
    min-height: 50px;
    border: 3px black solid;
}

.target {
    border-color: rgb(255, 0, 0);
}

.source {
    border-color: rgb(9, 255, 0);
}
</style>