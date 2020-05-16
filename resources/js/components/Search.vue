<template>
  <div>
    <ais-instant-search :search-client="searchClient" index-name="threads">
      <div class="d-flex">
        <div>
          <ais-clear-refinements />
          <ais-refinement-list attribute="channel.name" searchable />
        </div>
        <div>
          <ais-search-box />
          <ais-hits>
            <div slot="item" slot-scope="{ item }">
              <h6 class="mt-3">{{ item.title }}</h6>
            </div>
          </ais-hits>
        </div>
      </div>
    </ais-instant-search>
  </div>
</template>
 
<script>
import algoliasearch from "algoliasearch/lite";
// import "instantsearch.css/themes/algolia-min.css";

export default {
  data() {
    return {
      searchClient: algoliasearch(
        "63H2FR4HMI",
        "6b72b72d7119234f0507dd31245d4e57"
      )
    };
  }
};
</script>

<style lang="scss" scoped>
</style>