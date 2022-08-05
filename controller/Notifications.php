<?php

class Notifications extends Controller
{

    public function report()
    {
        if (!session('admin_session')) {
            jsonOutput([
                'message' => 'forbidden',
                'statusCode' => 403
            ]);
        }

        $reportId = get('id') ?? false;

        if (!$reportId) {
            site_url('/admin');
        } else {
            $reportData = $this->model('adminModel')->getReportData($reportId);
            if ($reportData) {
                $this->view('notify/report', [
                    'data' => $reportData
                ]);
            } else {
                jsonOutput([
                    'message' => 'report not found',
                    'status' => 404
                ]);
            }
        }
    }

    /**
     * @return void
     */
    public function reportPost(): void
    {
        $text = post('text') ?? false;
        $userId = post('notifyUser') ?? false;
        $key = post('reportedPostKey') ?? false;

        if (!$text || !$userId || !$key) {
            jsonOutput('error!');
        } else {
            $postData = $this->model('postModel')->getPostWithKey($key);
            $postTitle = $postData->post_title;
            $responseText = '<strong>"' . $postTitle . '"</strong> isimli entry raporun yanıtlandı: ' . $text;

            $add = $this->model('adminModel')->addNotification($responseText, $userId);
            if ($add) {
                site_url('/admin');
            } else {
                jsonOutput('bildirim eklenirken bir sorun oluştu!');
            }
        }
    }

    /**
     * @return void
     */
    public function getNotifications(): void
    {
        $userId = get('userId');

        if (!$userId) {
            jsonOutput([
                'message' => 'id not found',
                'status' => 404
            ]);
        } else if (session('user_id') !== $userId) {
            jsonOutput([
                'message' => 'forbidden',
                'status' => 403
            ]);
        } else {

            $model = $this->model('adminModel');
            $get = $model->getUserNotifications((int) $userId);
            jsonOutput($get);

        }
    }

    public function delete()
    {
        $data = getData();
        $id = (int) $data->id;
        $submit = $data->submit;

        if (!$id || !$submit) {
            jsonOutput([
                'message' => 'data missing',
                'status' => 404
            ]);
        } else {
            $model = $this->model('adminModel');
            $get = $model->getNotification($id);

            if ($get->notify_user !== session('user_id')) {
                jsonOutput([
                    'message' => 'forbidden',
                    'status' => 403
                ]);
            } else {
                $delete = $model->deleteNotification($id);

                if ($delete) {
                    jsonOutput('deleted');
                } else {
                    jsonOutput('error');
                }
            }
        }
    }

}