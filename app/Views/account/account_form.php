<?= $this->extend('layouts/layout') ?>

<?= $this->section('title') ?><?= isset($account) ? 'アカウント編集' : 'アカウント登録' ?><?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi <?= isset($account) ? 'bi-pencil-square' : 'bi-person-plus' ?>"></i>
                        <?= isset($account) ? 'アカウント編集' : 'アカウント登録' ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session('errors')) : ?>
                        <div class="alert alert-danger">
                            <?php foreach (session('errors') as $error) : ?>
                                <div><?= esc($error) ?></div>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                    <form action="<?= isset($account) ? route_to('account.update', $account['id']) : route_to('account.store') ?>"
                        method="post" id="accountForm">
                        <?= csrf_field() ?>
                        <?php if (isset($account)): ?>
                            <input type="hidden" name="_method" value="PUT">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="resource_id" class="form-label">リソース</label>
                            <select name="resource_id" id="resource_id" class="form-select" required <?= isset($account) ? 'disabled' : '' ?>>
                                <option value="">リソースを選択</option>
                                <?php foreach ($resources as $res): ?>
                                    <option value="<?= esc($res['id']) ?>"
                                        <?= (isset($selectedResource) && $selectedResource['id'] == $res['id']) ? 'selected' : '' ?>>
                                        <?= esc($res['name']) ?> (<?= esc($res['hostname']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <!-- 選択肢を無効化している場合の hidden input -->
                            <?php if (isset($selectedResource)): ?>
                                <input type="hidden" name="resource_id" value="<?= esc($selectedResource['id']) ?>">
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">ユーザー名</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?= isset($account) ? esc($account['username']) : '' ?>" required
                                    maxlength="50" pattern="^[a-zA-Z0-9_.-]+$"
                                    title="半角英数字、アンダースコア(_)、ハイフン(-)、ドット(.)のみ使用できます。">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">パスワード <?= isset($account) ? '(変更する場合のみ入力)' : '' ?></label>
                                <input type="password" class="form-control" id="password" name="password"
                                    minlength="4" <?= isset($account) ? '' : 'required' ?>>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="connection_type" class="form-label">接続方法</label>
                                <select name="connection_type" id="connection_type" class="form-select" required>
                                    <option value="SSH" <?= (isset($account) && $account['connection_type'] == 'SSH') ? 'selected' : '' ?>>SSH</option>
                                    <option value="RDP" <?= (isset($account) && $account['connection_type'] == 'RDP') ? 'selected' : '' ?>>RDP</option>
                                    <option value="VNC" <?= (isset($account) && $account['connection_type'] == 'VNC') ? 'selected' : '' ?>>VNC</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="port" class="form-label">ポート番号 (任意)</label>
                                <input type="number" class="form-control" id="port" name="port"
                                    value="<?= isset($account) ? esc($account['port']) : '' ?>" min="-1" max="65535"
                                    oninput="validatePort()">
                                <div id="port-error" class="text-danger mt-1" style="display: none;">
                                    ポート番号は -1 または 1～65535 の間で入力してください。
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">説明 (任意)</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                maxlength="255"><?= isset($account) ? esc($account['description']) : '' ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= route_to('account.index') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> 戻る
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> <?= isset($account) ? '更新' : '登録' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validatePort() {
        const port = document.getElementById("port").value;
        const errorDiv = document.getElementById("port-error");

        if (port !== "" && (port < 1 || port > 65535) && port != -1) {
            errorDiv.style.display = "block";
        } else {
            errorDiv.style.display = "none";
        }
    }

    document.getElementById("accountForm").addEventListener("submit", function(event) {
        if (document.getElementById("port-error").style.display === "block") {
            event.preventDefault();
            alert("ポート番号の値が不正です。");
        }
    });
</script>

<?= $this->endSection() ?>
