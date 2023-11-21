<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDnsRecordRequest;
use App\Http\Requests\UpdateDnsRecordRequest;
use App\Models\DnsRecord;

class DnsRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDnsRecordRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DnsRecord $dnsRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DnsRecord $dnsRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDnsRecordRequest $request, DnsRecord $dnsRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DnsRecord $dnsRecord)
    {
        //
    }
}
