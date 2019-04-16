<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <title>Calculator - Care to Beauty</title>
    <style>
        button{width: 40px;}
    </style>
</head>
<body>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-12">
                        <input type="text" id="display" class="form-control w-75" readonly="readonly"/>
                    </div>
                </div>

                <div class="row my-1">
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-dark" data-role="7">7</button>
                        <button type="button" class="btn btn-outline-dark" data-role="8">8</button>
                        <button type="button" class="btn btn-outline-dark" data-role="9">9</button>
                        <button type="button" class="btn btn-outline-dark" data-role="/">/</button>
                        <button type="button" class="btn btn-outline-dark w-auto" data-role="MOD">MOD</button>
                        <button type="button" class="btn btn-outline-dark w-auto" data-role="Reset">Reset</button>
                    </div>
                </div>

                <div class="row my-1">
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-dark" data-role="4">4</button>
                        <button type="button" class="btn btn-outline-dark" data-role="5">5</button>
                        <button type="button" class="btn btn-outline-dark" data-role="6">6</button>
                        <button type="button" class="btn btn-outline-dark" data-role="*">*</button>
                    </div>
                </div>

                <div class="row my-1">
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-dark" data-role="1">1</button>
                        <button type="button" class="btn btn-outline-dark" data-role="2">2</button>
                        <button type="button" class="btn btn-outline-dark" data-role="3">3</button>
                        <button type="button" class="btn btn-outline-dark" data-role="-">-</button>
                    </div>
                </div>

                <div class="row my-1">
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-dark" data-role="0">0</button>
                        <button type="button" class="btn btn-outline-dark" data-role=",">,</button>
                        <button type="button" class="btn btn-outline-dark" data-role="=">=</button>
                        <button type="button" class="btn btn-outline-dark" data-role="+">+</button>
                    </div>
                </div>

            </div>
        </div>

    </div>
</body>
<script type="text/javascript">

    /**
     * Stack object to keep all the pushed values
     */
    var stack = {
        values: [],
        fromResult: false,
        current: function(){
            return this.values[this.values.length-1];
        },
        push: function(value){
            this.values.push(value);
        },
        update: function(value){
            this.values[this.values.length-1] += value.toString();
        },
        clear: function(){
            this.values = [];
            this.fromResult = false;
        },
        isEmpty: function(){
            return this.values.length === 0;
        },
        toString: function(){
            let displayString = '';
            for(let i = 0; i < this.values.length; i++){
                displayString += this.values[i] + ' ';
            }
            return displayString;
        }
    };

    /**
     *Update the text in the display input
     * @param text
     * @returns void
     */
    function updateDisplay(text){
        if(undefined === text) {
            $('#display').val(stack.toString());
        }else{
            $('#display').val(text);
        }
    }

    /**
     * Perform the action according to the pushed button
     * @returns void
     * */
    function doAction(){

        let pushedValue = $(this).data('role');

        if( $.isNumeric(pushedValue) ){

            if(stack.fromResult){
                stack.clear();
            }

            if( isOperator(stack.current()) || stack.isEmpty() ){
                stack.push(pushedValue);
            }else{
                stack.update(pushedValue);
            }

        }else if( isComma(pushedValue) ){

            if(stack.fromResult){
                stack.clear();
            }

            if( isOperator(stack.current()) || stack.isEmpty() ){
                stack.push('0'+pushedValue);
            }else{
                if(!hasComma(stack.current()) ){
                    stack.update(pushedValue);
                }
            }

        }else if( isOperator(pushedValue) ){

            stack.fromResult = false;
            if( !isOperator(stack.current()) && !stack.isEmpty() ){
                stack.push(pushedValue);
            }

        }else if( isEquals(pushedValue) ){

            $.ajax({
                method: 'POST',
                url: '/calculate',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {"stack" : stack.values},
                success: function( data, textStatus, jQxhr ){
                    if(data.bonus){
                        alert('Gotcha! You matched your result with our Random number'+data.randomNumber+'!');
                    }
                    stack.clear();
                    stack.push(data.result);
                    stack.fromResult = true;
                    updateDisplay()
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    stack.clear();
                    updateDisplay('ERRO')
                    console.log( jqXhr.responseJSON.message );
                }
            });

        }else if( isReset(pushedValue) ){

            stack.clear();

        }

        updateDisplay();

    }

    /**
     * Check if the current value in the stack has alread a comma
     * @param val
     * @returns {boolean}
     */
    function hasComma(val){
        return /,/.test(val);
    }

    /**
     * Check if the current value in the stack is an Operator
     * @param val
     * @returns {boolean}
     */
    function isOperator(val){
        return /[\+\*\/]|\-(?!\d)|MOD/.test(val);
    }

    /**
     * Check if the pushed value is a comma
     * @param val
     * @returns {boolean}
     */
    function isComma(val){
        return val === ',';
    }

    /**
     * Check if the pushed button is the Equals
     * @param val
     * @returns {boolean}
     */
    function isEquals(val){
        return val === '=';
    }

    /**
     * Check if the pushed button is the Reset
     * @param val
     * @returns {boolean}
     */
    function isReset(val){
        return val === 'Reset';
    }

    $(function(){
        /**
         * Load the click event for all the button in the calculator
         */
        $('button').click(doAction);
    });

</script>
</html>
