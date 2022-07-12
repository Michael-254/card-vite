<script setup>
defineProps({
    Data: Object,
    Activity: String,
    TimelineIndex: Number,
    childNumber: Number,
});
</script>
<template>
    <p class="font-bold text-red-500">
        {{ Activity }} {{ Data.timelines[TimelineIndex].start_date }} - {{ Data.timelines[TimelineIndex].end_date }}
    </p>
    <div class="flex justify-between pt-2">

        <div class="flex justify-center">
            <div class="block bg-white text-center">
                <div class="py-3 px-6 border-b border-gray-300">
                    {{ Activity }}
                </div>
                <div class="p-6">
                    <p class="text-gray-700 text-xs mb-4">
                        Documents attached and locations
                    </p>
                    <div v-for="$record in Data.confirmedrecords" :key="$record.id"
                        class="flex justify-between items-center space-x-2">
                        <div v-if="$record.activity_child.child_number == childNumber"
                            class="flex justify-between items-center space-x-2">
                            <label class="mt-2 text-sm font-bold" v-html="$record.record"></label>
                            <p class="text-gray-400" v-html="$record.pivot.location"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-center">
            <div class="block bg-white text-center">
                <div class="py-3 px-6 border-b border-gray-300">
                    {{ Activity }} Signatures
                </div>
                <div class="p-6">
                    <p class="text-gray-700 text-xs mb-4">
                        Persons who signed
                    </p>
                    <div v-for="$signature in Data.signatures" :key="$signature.id"
                        class="flex justify-between items-center space-x-2">
                        <div v-if="$signature.son_activity.child_number == childNumber"
                            class="flex justify-between items-center space-x-2">
                            <label class="mt-2 text-sm font-bold" v-html="$signature.role.role"></label>
                            <p class="text-gray-400" v-html="$signature.user.name"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</template>

