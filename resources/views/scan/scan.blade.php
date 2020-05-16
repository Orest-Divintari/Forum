@extends('layouts.app')

@section('content')



<ais-instant-search index-name="instant_search" :search-client="searchClient" inline-template>
    <ais-search-box />
    <ais-hits>
        <div slot="item" slot-scope="{ item }">
            <h2>{{ item.name }}</h2>
        </div>
    </ais-hits>
</ais-instant-search>

<script>
import algoliasearch from 'algoliasearch/lite';
import 'instantsearch.css/themes/algolia-min.css';

export default {
    data() {
        return {
            searchClient: algoliasearch(
                'B1G2GM9NG0',
                'aadef574be1f9252bb48d4ea09b5cfe5'
            ),
        };
    },
};
</script>

<style>
body {
    font-family: sans-serif;
    padding: 1em;
}
</style>


</ais-instant-search>

<script>
@endsection