<?php

namespace App\Http\Controllers\v1;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SampleController
{
    // TODO: v2用に書き直す
    // リクエストクラス、apiリソースを用いて返すなど
    // ファットコントローラーでシンプルな書き方
    public function index(Request $request)
    {
        try {
            // フロントから来たクエリパラメータをバリデーションルールに通す
            // バリデーションに失敗すると以降の処理を通らず、ValidationExceptionの例外を投げる
            $validatedInput = $request->validate([
                // numのクエリパラメータを数値で必須にしている
                'num' => ['integer', 'required'],
            ]);

            // 通ったクエリパラメータを取得して加工なり
            $num = $validatedInput['num'];

            // (ここでDB操作などバックエンドに関する処理を入れる)
            // たとえばDBのidとしてSELECTにかけるなど

            // フロントに返したい結果を配列で用意する
            $result = [
                'status'    => 200,
                'message'   => "you send {$num}",
                'detail'    => 'sucess.',
            ];

        // 失敗を捕捉する
        // バリデーションエラーの場合、こちらの配列を返す
        } catch (ValidationException $e) {
            $result = [
                'status'  => 400,
                'message' => 'client error!',
                'detail'  => $e->getMessage(),
            ];

        // その他のエラーの場合、こちらの配列を返す
        } catch (Exception $e) {
            $result = [
                'status'  => 500,
                'message' => 'server error!',
                'detail'  => $e->getMessage(),
            ];
        }
        // フロントで扱えるようにjsonに変換して出力する
        return response()->json($result);
    }

}
