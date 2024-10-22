<div class="w-full h-52 flex">
    <div
        class="flex-1 grid grid-cols-4 grid-rows-4 bg-white dark:text-gray-400 dark:bg-gray-700 rounded-xl overflow-clip dark:border-gray-900 divide-x divide-y dark:divide-gray-900 border">
        <input class="border-t border-l dark:border-gray-900" type="button" value="7" @click="dis('7')">
        <input type="button" value="8" @click="dis('8')">
        <input type="button" value="9" @click="dis('9')">
        <div
            class="flex flex-col flex-3 row-span-3 items-center justify-evenly bg-gray-900 text-white dark:bg-indigo-900 dark:text-gray-400 divide-y dark:divide-gray-900">
            <input class="w-full text-2xl" type="button" value="÷" @click="dis('/')">
            <input class="w-full text-2xl" type="button" value="×" @click="dis('*')">
            <input class="w-full text-2xl" type="button" value="-" @click="dis('-')">
            <input class="w-full text-2xl" type="button" value="+" @click="dis('+')">
        </div>
        <input type="button" value="4" @click="dis('4')">
        <input type="button" value="5" @click="dis('5')">
        <input type="button" value="6" @click="dis('6')">
        <input type="button" value="1" @click="dis('1')">
        <input type="button" value="2" @click="dis('2')">
        <input type="button" value="3" @click="dis('3')">
        <input type="button" value="." @click="dis('.')">
        <input type="button" value="0" @click="dis('0')">
        <input class="text-2xl bg-gray-900 text-white dark:bg-indigo-900 dark:text-gray-400" type="button"
            value="c" @click="clr()">
        <input class="w-full h-full text-2xl bg-gray-900 text-white dark:bg-indigo-900 dark:text-gray-400"
            type="button" value="=" @click="solve()">
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/10.6.4/math.min.js"
    integrity="sha512-iphNRh6dPbeuPGIrQbCdbBF/qcqadKWLa35YPVfMZMHBSI6PLJh1om2xCTWhpVpmUyb4IvVS9iYnnYMkleVXLA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function calculator() {
        return {
            amount: '',

            dis(val) {
                if (this.amount == 0) {
                    this.amount = '';
                }
                this.amount += val;
            },

            solve() {
                let x = this.amount;
                try {
                    let y = math.evaluate(x);
                    this.amount = y;
                } catch (error) {
                    this.amount = "Error";
                }
            },

            clr() {
                this.amount = 0;
            },
        }
    }
</script>
