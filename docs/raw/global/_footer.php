
    <div id="footer">
        {ce:build:extract file=build_path."../../config.php" vars="SHORTCODE_NAME|SHORTCODE_VERSION"}
            <p>{SHORTCODE_NAME} version {SHORTCODE_VERSION}</p>
        {/ce:build:extract}
    </div>

    </div>
</div>

</body>
</html>
