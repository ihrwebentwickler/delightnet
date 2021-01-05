<div class="margin-content">
    <p>Example of content-loop and dynamic language</p>
    {LOOP CONTENTHELLO}
        <div class="container">
            <div class="grid-wrapper">
                <div class="col-6">
                    <h3>{HELLOHEADER}</h3>
                </div>
                <div class="col-6 align-center">
                    <p>
                        {HELLOCONTENT}
                    </p>
                </div>
            </div>
        </div>
    {/LOOP}
</div>