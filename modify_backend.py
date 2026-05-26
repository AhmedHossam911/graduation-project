import os

web_path = r'c:\xampp\htdocs\graduation project\routes\web.php'
with open(web_path, 'r', encoding='utf-8') as f:
    web_content = f.read()

if 'claims.finalize' not in web_content:
    web_content = web_content.replace(
        "Route::post('/claims/{claim}/approve', [ClaimController::class, 'approve'])->name('claims.approve');",
        "Route::post('/claims/{claim}/approve', [ClaimController::class, 'approve'])->name('claims.approve');\n        Route::post('/claims/{claim}/finalize', [ClaimController::class, 'finalize'])->name('claims.finalize');"
    )
    with open(web_path, 'w', encoding='utf-8') as f:
        f.write(web_content)


ctrl_path = r'c:\xampp\htdocs\graduation project\app\Http\Controllers\Employee\Claims\ClaimController.php'
with open(ctrl_path, 'r', encoding='utf-8') as f:
    ctrl_content = f.read()

if 'public function finalize' not in ctrl_content:
    finalize_func = '''
    /**
     * Finalize the claim (Upload signed receipt for cheque payment).
     */
    public function finalize(Request , Claim )
    {
        ->validate([
            'signed_receipt' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

         = ->toArray();

        DB::transaction(function () use (, , ) {
             = ->membership->member_id;
             = ->file('signed_receipt')->store("members/{}/claims/{->id}", 'public');

            Attachment::create([
                'member_id' => ,
                'type'      => "claim_{->id}_signed_receipt",
                'file_path' => ,
            ]);

            ->update([
                'status' => 'delivered' // Or 'ready', as requested by the user
            ]);

            ->logAudit('finalize', 'claims', ->id, , ->fresh()->toArray());
        });

        return back()->with('success', 'تم رفع الإقرار الموقع ودفع الشيك بنجاح.');
    }
'''
    # insert before logAudit
    ctrl_content = ctrl_content.replace('    private function logAudit', finalize_func + '\n    private function logAudit')
    
    with open(ctrl_path, 'w', encoding='utf-8') as f:
        f.write(ctrl_content)

print("Done updating routes and controller")
