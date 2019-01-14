<?php
// namespace billiesworks
?>
            </article>
            <footer>
                <small>&copy; 2018 <a href="http://billies-works.com/">billies-works</a></small>
				<?php if (isset($loginId)) { ?>
                    <?php if ($loginId === 'se-ichi') { ?>
				        <small><a href="editList.php">登録商品編集</a></small>
						<small><a href="userList.php">ユーザー管理</a></small>
                    <?php } ?>
                <?php } ?>
            </footer>
        </div><!-- #wrap -->
        <script src="js/sendWatchItem.js"></script>
		<script src="js/ama.js"></script>
        <script>FontAwesomeConfig = { searchPseudoElements: true };</sctipt>
    </body>
</html>








