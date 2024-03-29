<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\Admin\MemberResource;
use App\Models\Member;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MembersApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('member_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MemberResource(Member::all());
    }

    public function store(StoreMemberRequest $request)
    {
        $member = Member::create($request->all());

        if ($request->input('photograph', false)) {
            $member->addMedia(storage_path('tmp/uploads/' . basename($request->input('photograph'))))->toMediaCollection('photograph');
        }

        if ($request->input('signed_document', false)) {
            $member->addMedia(storage_path('tmp/uploads/' . basename($request->input('signed_document'))))->toMediaCollection('signed_document');
        }

        return (new MemberResource($member))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Member $member)
    {
        abort_if(Gate::denies('member_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MemberResource($member);
    }

    public function update(UpdateMemberRequest $request, Member $member)
    {
        $member->update($request->all());

        if ($request->input('photograph', false)) {
            if (!$member->photograph || $request->input('photograph') !== $member->photograph->file_name) {
                if ($member->photograph) {
                    $member->photograph->delete();
                }
                $member->addMedia(storage_path('tmp/uploads/' . basename($request->input('photograph'))))->toMediaCollection('photograph');
            }
        } elseif ($member->photograph) {
            $member->photograph->delete();
        }

        if ($request->input('signed_document', false)) {
            if (!$member->signed_document || $request->input('signed_document') !== $member->signed_document->file_name) {
                if ($member->signed_document) {
                    $member->signed_document->delete();
                }
                $member->addMedia(storage_path('tmp/uploads/' . basename($request->input('signed_document'))))->toMediaCollection('signed_document');
            }
        } elseif ($member->signed_document) {
            $member->signed_document->delete();
        }

        return (new MemberResource($member))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Member $member)
    {
        abort_if(Gate::denies('member_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $member->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
