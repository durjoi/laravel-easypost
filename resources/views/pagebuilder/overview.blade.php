<?php
$pagesTabActive = ! isset($_GET['tab']) || $_GET['tab'] === 'pages' ? 'active' : '';
$menusTabActive = isset($_GET['tab']) && $_GET['tab'] === 'menus' ? 'active' : '';
$settingsTabActive = isset($_GET['tab']) && $_GET['tab'] === 'settings' ? 'active' : '';
?>
<!-- <div class="py-5 text-center">
    <h2><?= phpb_trans('website-manager.title') ?></h2>
</div> -->

<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?= phpb_e($pagesTabActive) ?>" data-toggle="tab" href="#pages"><?= phpb_trans('website-manager.pages') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= phpb_e($menusTabActive) ?>" data-toggle="tab" href="#menus"><?= phpb_trans('website-manager.menus') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= phpb_e($settingsTabActive) ?>" data-toggle="tab" href="#settings"><?= phpb_trans('website-manager.settings') ?></a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="pages" class="tab-pane <?= phpb_e($pagesTabActive) ?>">

                <h4><?= phpb_trans('website-manager.pages') ?></h4>

                <div class="main-spacing">
                    <?php
                    if (phpb_flash('message')):
                    ?>
                    <div class="alert alert-<?= phpb_flash('message-type') ?>">
                        <?= phpb_flash('message') ?>
                    </div>
                    <?php
                    endif;
                    ?>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col"><?= phpb_trans('website-manager.name') ?></th>
                                    <th scope="col"><?= phpb_trans('website-manager.route') ?></th>
                                    <th scope="col"><?= phpb_trans('website-manager.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($pages as $page):
                                ?>
                                <tr>
                                    <td>
                                        <?= phpb_e($page->getName()) ?>
                                    </td>
                                    <td>
                                        <?= phpb_e($page->getRoute()) ?>
                                    </td>
                                    <td class="actionss">
                                        <a href="../../<?= phpb_e($page->getRoute()) ?>" target="_blank" class="btn btn-light btn-sm">
                                            <span><?= phpb_trans('website-manager.view') ?></span> <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= phpb_url('pagebuilder', ['page' => $page->getId()]) ?>" class="btn btn-primary btn-sm">
                                            <span><?= phpb_trans('website-manager.edit') ?></span> <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= phpb_url('website_manager', ['route' => 'page_settings', 'action' => 'edit', 'page' => $page->getId()]) ?>" class="btn btn-secondary btn-sm">
                                            <span><?= phpb_trans('website-manager.settings') ?></span> <i class="fas fa-cog"></i>
                                        </a>
                                        <a href="<?= phpb_url('website_manager', ['route' => 'page_settings', 'action' => 'destroy', 'page' => $page->getId()]) ?>" class="btn btn-danger btn-sm">
                                            <span><?= phpb_trans('website-manager.remove') ?></span> <i class="fas fa-trash"></i>
                                        </a>
                                        
                                        <!-- <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="action-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                            <div class="dropdown-menu" aria-labelledby="action-btn">
                                                <a class="dropdown-item font14px" href="{{ '../'.$page->url }}" target="_blank"><i class="fa fa-eye fa-fw"></i> View</a>
                                                <a class="dropdown-item font14px" href="{{ url('admin/pagebuilder/'.$page->hashedid.'/tags') }}"><i class="fa fa-hashtag fa-fw"></i> Meta Tags</a>
                                                <a class="dropdown-item font14px edit-page" data-attr-identification="{{ $page->id }}" data-attr-id="{{ $page->hashedid }}" href="javascript:void(0);"><i class="fa fa-edit fa-fw"></i> Edit</a>
                                                <a class="dropdown-item font14px"href="{{ url('admin/pagebuilder/'.$page->hashedid.'/build') }}"><i class="fa fa-cog fa-fw"></i> Settings</a>
                                                <a class="dropdown-item font14px" href="javascript:void(0)"><i class="fa fa-trash-alt fa-fw"></i> Delete</a>
                                            </div>
                                        </div> -->
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="mb-3">
                <a href="<?= phpb_url('website_manager', ['route' => 'page_settings', 'action' => 'create']) ?>" class="btn btn-primary btn-sm">
                    <?= phpb_trans('website-manager.add-new-page') ?>
                </a>

            </div>
            <div id="menus" class="tab-pane <?= phpb_e($menusTabActive) ?>">

                <h4 class="mb-3"><?= phpb_trans('website-manager.menus') ?></h4>

            </div>
            <div id="settings" class="tab-pane <?= phpb_e($settingsTabActive) ?>">

                <h4 class="mb-3"><?= phpb_trans('website-manager.settings') ?></h4>

                <?php
                require __DIR__ . '/settings.php';
                ?>

            </div>
        </div>
    </div>
</div>
