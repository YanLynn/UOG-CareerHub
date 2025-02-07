<template>
    <div>
        <DataTable :value="data" paginator :rows="rows" dataKey="id" filterDisplay="menu" :loading="loading"
            :globalFilterFields="globalFilterFields" v-model:filters="filters">
            <template #header>
                <div class="flex justify-between">
                    <Button type="button" icon="pi pi-filter-slash" label="Clear" outlined @click="clearFilter" />
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Keyword Search"
                            @input="applyFilter" />
                    </IconField>
                </div>
            </template>

            <template #empty> No data found. </template>
            <template #loading> Loading data. Please wait. </template>
            <Column v-for="col in columns" :key="col.field" :field="col.field" :header="col.header" :sortable="true">
                <template v-if="$slots[`body-${col.field}`]" #body="slotProps">
                    <slot :name="`body-${col.field}`" :data="slotProps.data"></slot>
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<script setup>
import { ref, defineProps } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';

const props = defineProps({
    data: Array,
    loading: Boolean,
    columns: Array,
    rows: { type: Number, default: 10 },
    globalFilterFields: Array,
});

const filters = ref({
    global: { value: '', matchMode: FilterMatchMode.CONTAINS },
});

props.columns.forEach(column => {
    filters.value[column.field] = { value: null, matchMode: column.matchMode || FilterMatchMode.STARTS_WITH };
});


const applyFilter = () => {
    filters.value = { ...filters.value };
};

const clearFilter = () => {
    Object.keys(filters.value).forEach(key => {
        filters.value[key].value = null;
    });
    filters.value.global.value = '';
};
</script>
