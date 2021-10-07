<template>
  <th @click="selectedColumn(column)">
    <slot />
    <i class="fa fa-angle-up"  v-if="filter.order_column == column &&  orderBy == 'asc'"></i>
    <i class="fa fa-angle-down" v-else-if="filter.order_column == column &&  orderBy == 'desc'"></i>
    <i class="fa fa-angle-up fa-angle-down" v-else></i>
  </th>
</template>
<style scoped>
th{
    cursor: pointer;
}
</style>

<script>
export default {
  props: {
    filter: {
      type: Array,
      default: {}
    },
    clickHandler: {
        type: Function,
        default: () => {}
    },
    column: {
      type: String,
      default: ''
    },
    title: {
      type: String,
      default: ''
    },
  },
  data () {
      return {
                orderBy: this.filter.order_by ?? "",
            }
  },
  methods: {
    selectedColumn(column){
        if(this.filter.order_column != column)
        this.orderBy = this.filter.order_by == 'desc';
        else
        this.orderBy = this.filter.order_by == 'asc' ? 'desc' : 'asc';
        this.clickHandler(column, this.orderBy);
    },
  }
}
</script>